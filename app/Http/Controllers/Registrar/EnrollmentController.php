<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentRequest;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Enrollment;
use App\Models\Verification;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function show(Applicant $applicant)
    {
        $applicant->load([
            'preferredCourse', 'academicTerm', 'verification', 'latestExamResult', 'enrollment',
        ]);

        return view('registrar.enrollment-finalize', compact('applicant'));
    }

    /**
     * Finalize enrollment. Per business decision the enrollment step is
     * intentionally minimal:
     *  - no section assignment
     *  - no payment
     *  - no COR generation
     * Only requirement: applicant's documents have been verified.
     * On success the applicant is flipped to STATUS_ENROLLED. The
     * confirmation surfaces on their status page.
     */
    public function finalize(EnrollmentRequest $request)
    {
        try {
            $enrollment = DB::transaction(function () use ($request) {
                /** @var Applicant $applicant */
                $applicant = Applicant::with('verification', 'enrollment')
                    ->lockForUpdate()
                    ->findOrFail($request->applicant_id);

                if ($applicant->status === Applicant::STATUS_ENROLLED) {
                    throw new \RuntimeException('Applicant is already enrolled.');
                }
                if ($applicant->status === Applicant::STATUS_REJECTED) {
                    throw new \RuntimeException('Rejected applicants cannot be enrolled.');
                }

                $verification = $applicant->verification;
                if (! $verification || $verification->status !== Verification::STATUS_VERIFIED) {
                    throw new \RuntimeException('Applicant documents are not fully verified.');
                }

                $enrollmentNo = $this->nextEnrollmentNo($applicant->academic_term_id);

                $enrollment = Enrollment::create([
                    'enrollment_no'    => $enrollmentNo,
                    'applicant_id'     => $applicant->id,
                    'course_id'        => $applicant->preferred_course_id,
                    'section_id'       => null,
                    'academic_term_id' => $applicant->academic_term_id,
                    'processed_by'     => $request->user()->id,
                    'status'           => Enrollment::STATUS_FINALIZED,
                    'enrolled_at'      => now(),
                ]);

                $applicant->update(['status' => Applicant::STATUS_ENROLLED]);

                AuditLog::record('enrollment.finalize', $enrollment, [
                    'enrollment_no' => $enrollment->enrollment_no,
                    'registrar_id'  => $request->user()->id,
                ]);

                return $enrollment->fresh(['applicant']);
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['enrollment' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('registrar.verify.show', $enrollment->applicant_id)
            ->with('status', "Enrollment finalized. {$enrollment->enrollment_no}");
    }

    protected function nextEnrollmentNo(?int $termId): string
    {
        $year  = date('Y');
        $count = Enrollment::where('academic_term_id', $termId)->count() + 1;
        return sprintf('ENR-%s-%06d', $year, $count);
    }
}
