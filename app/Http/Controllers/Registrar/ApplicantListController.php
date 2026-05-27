<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicantListController extends Controller
{
    protected function baseQuery(Request $request)
    {
        $q = Applicant::with(['preferredCourse', 'academicTerm', 'latestExamResult']);

        if ($search = $request->input('q')) {
            $q->where(fn ($w) =>
                $w->where('reference_code', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%"));
        }
        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }
        if ($courseId = $request->input('course_id')) {
            $q->where('preferred_course_id', $courseId);
        }
        if ($termId = $request->input('academic_term_id')) {
            $q->where('academic_term_id', $termId);
        }

        return $q;
    }

    public function index(Request $request)
    {
        $applicants = $this->baseQuery($request)->latest()->paginate(25)->withQueryString();

        return view('registrar.applicants', [
            'applicants' => $applicants,
            'courses'    => Course::orderBy('code')->get(),
            'terms'      => AcademicTerm::orderBy('school_year', 'desc')->get(),
            'filter'     => $request->only(['q', 'status', 'course_id', 'academic_term_id']),
            'statuses'   => [
                Applicant::STATUS_PRE_REGISTERED, Applicant::STATUS_EXAM_SCHEDULED,
                Applicant::STATUS_EXAM_COMPLETED, Applicant::STATUS_VERIFIED,
                Applicant::STATUS_ENROLLED, Applicant::STATUS_REJECTED,
            ],
        ]);
    }

    public function export(Request $request): Response
    {
        $filename = 'applicants-' . now()->format('Ymd-His') . '.csv';

        $out = fopen('php://temp', 'w+');
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'Reference', 'Last Name', 'First Name', 'Middle Name',
            'Gender', 'Birth Date', 'Mobile', 'Email',
            'Course', 'Term', 'Status',
            'Latest Exam Score', 'Latest Exam Result', 'Registered At',
        ]);

        $this->baseQuery($request)->orderBy('id')->chunk(500, function ($rows) use ($out) {
            foreach ($rows as $a) {
                fputcsv($out, [
                    $a->reference_code,
                    $a->last_name, $a->first_name, $a->middle_name,
                    $a->gender, optional($a->birth_date)->format('Y-m-d'),
                    $a->mobile, $a->email,
                    $a->preferredCourse?->code,
                    trim(($a->academicTerm?->school_year ?? '') . ' ' . ($a->academicTerm?->semester ?? '')),
                    $a->status,
                    $a->latestExamResult?->score,
                    $a->latestExamResult?->result,
                    $a->created_at?->format('Y-m-d H:i'),
                ]);
            }
        });

        rewind($out);
        $csv = stream_get_contents($out) ?: '';
        fclose($out);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function destroy(Request $request, Applicant $applicant)
    {
        if ($applicant->status === Applicant::STATUS_ENROLLED) {
            return back()->withErrors(['delete' => 'Enrolled students cannot be deleted from this page.']);
        }

        AuditLog::record('registrar.applicant.delete', $applicant, [
            'reference_code' => $applicant->reference_code,
            'deleted_by' => $request->user()->id,
        ]);

        $applicant->delete();

        return back()->with('status', 'Applicant deleted.');
    }
}
