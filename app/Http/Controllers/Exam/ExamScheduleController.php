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
        return view('exam.schedule', [
            'schedules' => ExamSchedule::with('academicTerm')->latest('exam_datetime')->paginate(15),
            'terms'     => AcademicTerm::where('is_active', true)->get(),
            'pending'   => Applicant::where('status', Applicant::STATUS_PRE_REGISTERED)->get(),
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
        $request->validate([
            'applicant_ids'   => ['required', 'array'],
            'applicant_ids.*' => ['integer', 'exists:applicants,id'],
        ]);

        $assigned = DB::transaction(function () use ($request, $schedule) {
            $schedule = ExamSchedule::lockForUpdate()->find($schedule->id);
            $this->capacity->assertExamScheduleCapacity($schedule);

            $ids = $request->input('applicant_ids');
            $remaining = $schedule->remainingSlots();
            $ids = array_slice($ids, 0, $remaining);

            $count = 0;
            foreach ($ids as $applicantId) {
                $exists = ExamResult::where('applicant_id', $applicantId)
                    ->where('exam_schedule_id', $schedule->id)->exists();
                if ($exists) continue;

                ExamResult::create([
                    'applicant_id'     => $applicantId,
                    'exam_schedule_id' => $schedule->id,
                    'result'           => ExamResult::RESULT_PENDING,
                    'sms_status'       => ExamResult::SMS_NOT_SENT,
                ]);

                Applicant::where('id', $applicantId)
                    ->where('status', Applicant::STATUS_PRE_REGISTERED)
                    ->update(['status' => Applicant::STATUS_EXAM_SCHEDULED]);

                $count++;
            }

            $schedule->increment('assigned_count', $count);
            AuditLog::record('exam.schedule.assign_batch', $schedule, ['count' => $count]);

            return $count;
        });

        return back()->with('status', "Assigned {$assigned} applicant(s) to batch.");
    }
}
