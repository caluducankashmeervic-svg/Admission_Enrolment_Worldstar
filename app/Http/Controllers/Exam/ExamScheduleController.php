<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Services\CapacityEnforcer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{
    public function __construct(protected CapacityEnforcer $capacity) {}

    public function index()
    {
        $schedules = ExamSchedule::with(['academicTerm', 'examResults.applicant'])
            ->latest('exam_datetime')
            ->paginate(15);

        return view('exam.schedule', [
            'schedules' => $schedules,
            'terms'     => AcademicTerm::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'batch_code'       => ['required', 'string', 'max:30', 'unique:exam_schedules,batch_code'],
            'exam_datetime'    => ['required', 'date', 'after:now'],
            'venue'            => ['required', 'string', 'max:150'],
            'capacity'         => ['required', 'integer', 'min:1', 'max:1000'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ]);

        $schedule = ExamSchedule::create($data);
        AuditLog::record('exam.schedule.create', $schedule);

        return back()->with('status', "Exam batch {$schedule->batch_code} created.");
    }

    public function assignBatch(Request $request, ExamSchedule $schedule)
    {
        // Deprecated: auto-assignment now happens on registrar approval.
        abort(410, 'Manual batch assignment has been removed. Approve the applicant from the registrar lookup page; assignment is automatic (FCFS).');
    }

    /**
     * Remove an applicant from an exam batch. Frees a slot and moves the
     * applicant back to pre_registered so a registrar can re-approve them
     * (and the FCFS picker will assign the next open batch).
     */
    public function removeApplicant(Request $request, ExamSchedule $schedule, Applicant $applicant)
    {
        DB::transaction(function () use ($schedule, $applicant) {
            $schedule = ExamSchedule::lockForUpdate()->find($schedule->id);
            $result = ExamResult::where('applicant_id', $applicant->id)
                ->where('exam_schedule_id', $schedule->id)
                ->lockForUpdate()
                ->first();

            if (! $result) {
                return;
            }
            // Refuse to remove if the exam has already been scored.
            if ($result->result !== ExamResult::RESULT_PENDING) {
                throw new \RuntimeException("Cannot remove {$applicant->reference_code}: exam already scored.");
            }

            $result->delete();
            $schedule->decrement('assigned_count');

            Applicant::where('id', $applicant->id)
                ->where('status', Applicant::STATUS_EXAM_SCHEDULED)
                ->update(['status' => Applicant::STATUS_PRE_REGISTERED]);

            AuditLog::record('exam.schedule.remove_applicant', $schedule, [
                'applicant_id' => $applicant->id,
            ]);
        });

        return back()->with('status', "Removed {$applicant->reference_code} from batch.");
    }

    /**
     * Move an applicant from one batch to another (manual override of FCFS).
     */
    public function moveApplicant(Request $request, ExamSchedule $schedule, Applicant $applicant)
    {
        $data = $request->validate([
            'target_schedule_id' => ['required', 'integer', 'exists:exam_schedules,id'],
        ]);

        DB::transaction(function () use ($schedule, $applicant, $data) {
            $from = ExamSchedule::lockForUpdate()->find($schedule->id);
            $to   = ExamSchedule::lockForUpdate()->find($data['target_schedule_id']);

            if ($from->id === $to->id) {
                return;
            }

            $this->capacity->assertExamScheduleCapacity($to);

            $result = ExamResult::where('applicant_id', $applicant->id)
                ->where('exam_schedule_id', $from->id)
                ->lockForUpdate()
                ->first();
            if (! $result) {
                return;
            }
            if ($result->result !== ExamResult::RESULT_PENDING) {
                throw new \RuntimeException("Cannot move {$applicant->reference_code}: exam already scored.");
            }

            $result->update(['exam_schedule_id' => $to->id]);
            $from->decrement('assigned_count');
            $to->increment('assigned_count');

            AuditLog::record('exam.schedule.move_applicant', $to, [
                'applicant_id' => $applicant->id,
                'from_batch'   => $from->batch_code,
                'to_batch'     => $to->batch_code,
            ]);
        });

        return back()->with('status', "Moved {$applicant->reference_code} to new batch.");
    }
}
