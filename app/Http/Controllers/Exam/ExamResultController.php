<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Services\SmsGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    public function __construct(protected SmsGateway $sms) {}

    public function index(ExamSchedule $schedule)
    {
        $results = ExamResult::with('applicant')
            ->where('exam_schedule_id', $schedule->id)
            ->orderBy('id')->get();

        return view('exam.results', compact('schedule', 'results'));
    }

    public function postScores(Request $request, ExamSchedule $schedule)
    {
        $data = $request->validate([
            'passing_score'   => ['required', 'numeric', 'min:0', 'max:100'],
            'results'         => ['required', 'array'],
            'results.*.id'    => ['required', 'integer', 'exists:exam_results,id'],
            'results.*.score' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($data, $schedule) {
            foreach ($data['results'] as $row) {
                $result = ExamResult::where('id', $row['id'])
                    ->where('exam_schedule_id', $schedule->id)->first();
                if (! $result) continue;

                $passed = $row['score'] >= $data['passing_score'];
                $result->update([
                    'score'  => $row['score'],
                    'result' => $passed ? ExamResult::RESULT_PASSED : ExamResult::RESULT_FAILED,
                ]);

                Applicant::where('id', $result->applicant_id)
                    ->update(['status' => Applicant::STATUS_EXAM_COMPLETED]);
            }
            AuditLog::record('exam.results.post', $schedule, ['count' => count($data['results'])]);
        });

        return back()->with('status', 'Exam results recorded.');
    }

    public function dispatchSms(ExamSchedule $schedule)
    {
        $results = ExamResult::with('applicant')
            ->where('exam_schedule_id', $schedule->id)
            ->whereIn('result', [ExamResult::RESULT_PASSED, ExamResult::RESULT_FAILED])
            ->where('sms_status', '!=', ExamResult::SMS_SENT)
            ->get();

        $sent = 0; $failed = 0;
        foreach ($results as $r) {
            $applicant = $r->applicant;
            if (! $applicant || ! $applicant->mobile) continue;

            $status = strtoupper($r->result);
            $message = "Hello {$applicant->first_name}, your entrance exam result is: {$status}. "
                     . "Score: {$r->score}. Ref: {$applicant->reference_code}.";

            $res = $this->sms->send($applicant->mobile, $message);

            $r->update([
                'sms_status'       => $res['status'] === 'sent' ? ExamResult::SMS_SENT : ExamResult::SMS_FAILED,
                'sms_sent_at'      => $res['status'] === 'sent' ? now() : null,
                'sms_provider_ref' => $res['ref'],
            ]);

            $res['status'] === 'sent' ? $sent++ : $failed++;
        }

        AuditLog::record('exam.results.sms_dispatch', $schedule, compact('sent', 'failed'));
        return back()->with('status', "SMS dispatch complete: {$sent} sent, {$failed} failed.");
    }
}
