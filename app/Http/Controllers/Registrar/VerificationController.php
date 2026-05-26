<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\Verification;
use App\Services\CapacityEnforcer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function __construct(protected CapacityEnforcer $capacity) {}

    public function lookup()
    {
        return view('registrar.lookup');
    }

    public function find(Request $request)
    {
        $request->validate([
            'reference_code' => ['required', 'string', 'max:20'],
        ]);

        $applicant = Applicant::with([
            'preferredCourse', 'academicTerm', 'latestExamResult.examSchedule', 'verification', 'enrollment',
        ])->where('reference_code', $request->reference_code)->first();

        if (! $applicant) {
            return back()->withErrors(['reference_code' => 'Reference code not found.'])
                ->withInput();
        }

        AuditLog::record('registrar.lookup', $applicant);
        return redirect()->route('registrar.verify.show', $applicant->id);
    }

    public function show(Applicant $applicant)
    {
        $applicant->load([
            'preferredCourse', 'academicTerm', 'latestExamResult.examSchedule', 'verification',
        ]);

        return view('registrar.verify', compact('applicant'));
    }

    /**
     * Approve the pre-registration form. Atomically:
     *  - validates applicant is still in pre_registered state
     *  - picks earliest open exam batch (FCFS by exam_datetime, locked)
     *  - creates the ExamResult row, bumps the batch counter
     *  - moves applicant status to exam_scheduled
     * If no open batch exists, approval is blocked (Q1: block, not queue).
     */
    public function approve(Request $request, Applicant $applicant)
    {
        if ($applicant->status !== Applicant::STATUS_PRE_REGISTERED) {
            return back()->with('status', 'This applicant has already been approved.');
        }

        try {
            DB::transaction(function () use ($applicant, $request) {
                // Re-lock to prevent double-approve races.
                $locked = Applicant::whereKey($applicant->id)->lockForUpdate()->first();
                if (! $locked || $locked->status !== Applicant::STATUS_PRE_REGISTERED) {
                    return;
                }

                $batch = $this->capacity->pickEarliestOpenBatch();
                if (! $batch) {
                    throw new \RuntimeException(
                        'No exam batches with open slots are available. Create or open a batch before approving.'
                    );
                }

                ExamResult::create([
                    'applicant_id'     => $locked->id,
                    'exam_schedule_id' => $batch->id,
                    'result'           => ExamResult::RESULT_PENDING,
                    'sms_status'       => ExamResult::SMS_NOT_SENT,
                ]);

                $batch->increment('assigned_count');

                $locked->update([
                    'status'           => Applicant::STATUS_EXAM_SCHEDULED,
                    'academic_term_id' => $locked->academic_term_id ?: $batch->academic_term_id,
                ]);

                AuditLog::record('applicant.form_approved', $locked, [
                    'batch_code'   => $batch->batch_code,
                    'registrar_id' => $request->user()->id,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['approve' => $e->getMessage()]);
        }

        return back()->with('status', 'Pre-registration approved. Applicant has been assigned to an exam batch.');
    }

    /**
     * Reject an application. Works at any stage prior to enrollment.
     * If the applicant currently holds an exam-batch slot with a pending
     * result, the slot is freed and the batch counter decremented so another
     * applicant can be auto-assigned via FCFS.
     */
    public function reject(Request $request, Applicant $applicant)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if (in_array($applicant->status, [Applicant::STATUS_ENROLLED, Applicant::STATUS_REJECTED], true)) {
            return back()->withErrors(['reject' => 'Applicant cannot be rejected from the current status.']);
        }

        DB::transaction(function () use ($applicant, $data, $request) {
            $locked = Applicant::whereKey($applicant->id)->lockForUpdate()->first();
            if (! $locked || in_array($locked->status, [Applicant::STATUS_ENROLLED, Applicant::STATUS_REJECTED], true)) {
                return;
            }

            $pendingResult = ExamResult::where('applicant_id', $locked->id)
                ->where('result', ExamResult::RESULT_PENDING)
                ->lockForUpdate()
                ->first();

            if ($pendingResult) {
                $schedule = ExamSchedule::whereKey($pendingResult->exam_schedule_id)->lockForUpdate()->first();
                $pendingResult->delete();
                if ($schedule && $schedule->assigned_count > 0) {
                    $schedule->decrement('assigned_count');
                }
            }

            $locked->update(['status' => Applicant::STATUS_REJECTED]);

            AuditLog::record('applicant.rejected', $locked, [
                'reason'       => $data['reason'],
                'registrar_id' => $request->user()->id,
                'prior_status' => $applicant->status,
            ]);
        });

        return back()->with('status', 'Application rejected.');
    }

    public function store(Request $request, Applicant $applicant)
    {
        // Server-side guard: documents may only be verified after the applicant has
        // been assigned to an exam batch (and therefore has an exam record).
        $applicant->loadMissing('latestExamResult');
        $exam = $applicant->latestExamResult;
        if (! $exam) {
            return back()->withErrors([
                'exam' => 'This applicant has not been assigned to an exam batch yet. Assign an exam batch before verifying documents.',
            ]);
        }

        // Exam score must be in before docs can be verified.
        if ($exam->result === \App\Models\ExamResult::RESULT_PENDING) {
            return back()->withErrors([
                'exam' => 'The entrance exam has not been scored yet. Documents cannot be verified until a score is recorded.',
            ]);
        }

        // Failed applicants are blocked by default. A registrar may override
        // with a written reason that is preserved on the verification record
        // and surfaced on the applicant's status page.
        $rules = [
            'doc_form_137'       => ['sometimes', 'boolean'],
            'doc_psa_birth_cert' => ['sometimes', 'boolean'],
            'doc_good_moral'     => ['sometimes', 'boolean'],
            'doc_id_photos'      => ['sometimes', 'boolean'],
            'doc_medical_cert'   => ['sometimes', 'boolean'],
            'doc_diploma'        => ['sometimes', 'boolean'],
            'remarks'            => ['nullable', 'string', 'max:500'],
        ];
        if ($exam->result === \App\Models\ExamResult::RESULT_FAILED) {
            $rules['override_reason'] = ['required', 'string', 'min:5', 'max:500'];
        } else {
            $rules['override_reason'] = ['nullable', 'string', 'max:500'];
        }
        $data = $request->validate($rules);

        $docs = collect(Verification::REQUIRED_DOCS)
            ->mapWithKeys(fn ($d) => [$d => (bool) ($data[$d] ?? false)])
            ->all();

        $verification = Verification::updateOrCreate(
            ['applicant_id' => $applicant->id],
            array_merge($docs, [
                'registrar_id'    => $request->user()->id,
                'remarks'         => $data['remarks'] ?? null,
                'override_reason' => $exam->result === \App\Models\ExamResult::RESULT_FAILED
                    ? $data['override_reason']
                    : null,
            ])
        );

        $complete = $verification->allDocumentsComplete();
        $verification->update([
            'status'      => $complete ? Verification::STATUS_VERIFIED : Verification::STATUS_INCOMPLETE,
            'verified_at' => $complete ? now() : null,
        ]);

        if ($complete && $applicant->status !== Applicant::STATUS_ENROLLED) {
            $applicant->update(['status' => Applicant::STATUS_VERIFIED]);
        }

        AuditLog::record('registrar.verification.save', $applicant, [
            'status'      => $verification->status,
            'exam_result' => $exam->result,
            'override'    => (bool) $verification->override_reason,
        ]);

        return back()->with('status', $complete
            ? 'All documents verified. Applicant is ready for enrollment.'
            : 'Verification saved. Some documents are still missing.');
    }
}
