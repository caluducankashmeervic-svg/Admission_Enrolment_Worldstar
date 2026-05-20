<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnrollmentListController extends Controller
{
    protected function baseQuery(Request $request)
    {
        $q = Enrollment::with(['applicant', 'course', 'section', 'academicTerm', 'processor']);

        if ($search = $request->input('q')) {
            $q->where(fn ($w) =>
                $w->where('enrollment_no', 'like', "%$search%")
                  ->orWhereHas('applicant', fn ($a) =>
                        $a->where('reference_code', 'like', "%$search%")
                          ->orWhere('first_name', 'like', "%$search%")
                          ->orWhere('last_name', 'like', "%$search%")));
        }
        if ($courseId = $request->input('course_id')) {
            $q->where('course_id', $courseId);
        }
        if ($termId = $request->input('academic_term_id')) {
            $q->where('academic_term_id', $termId);
        }
        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }

        return $q;
    }

    public function index(Request $request)
    {
        return view('registrar.enrollments', [
            'enrollments' => $this->baseQuery($request)->latest('enrolled_at')
                                    ->paginate(25)->withQueryString(),
            'courses'     => Course::orderBy('code')->get(),
            'terms'       => AcademicTerm::orderBy('school_year', 'desc')->get(),
            'filter'      => $request->only(['q', 'course_id', 'academic_term_id', 'status']),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'enrollments-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Enrollment No.', 'Reference', 'Student',
                'Course', 'Section', 'Term', 'Status',
                'Processed By', 'Enrolled At',
            ]);

            $this->baseQuery($request)->orderBy('id')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $e) {
                    fputcsv($out, [
                        $e->enrollment_no,
                        $e->applicant?->reference_code,
                        $e->applicant?->full_name,
                        $e->course?->code,
                        $e->section?->name,
                        trim(($e->academicTerm?->school_year ?? '') . ' ' . ($e->academicTerm?->semester ?? '')),
                        $e->status,
                        $e->processor?->name,
                        optional($e->enrolled_at)->format('Y-m-d H:i'),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
