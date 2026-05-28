<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Announcement;
use App\Models\Applicant;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ExamSchedule;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $term = AcademicTerm::where('is_active', true)->latest('school_year')->first();
        [$selectedTermId, $selectedTermLabel] = $this->resolveTermFilter(request());

        return view('admin.dashboard', [
            'term'              => $term,
            'summary'           => $this->summary($selectedTermId),
            'terms'             => AcademicTerm::orderByDesc('school_year')->orderBy('semester')->get(),
            'selectedTermId'    => $selectedTermId,
            'selectedTermLabel' => $selectedTermLabel,
            'announcementCount' => Announcement::where('is_active', true)->count(),
            'upcomingExams'     => ExamSchedule::where('exam_datetime', '>=', now())
                                        ->orderBy('exam_datetime')
                                        ->take(4)
                                        ->get(['id', 'batch_code', 'exam_datetime', 'venue', 'assigned_count', 'capacity']),
            'recentActivity'    => Applicant::with('preferredCourse:id,code')
                                        ->latest()
                                        ->take(6)
                                        ->get(['id', 'first_name', 'last_name', 'status', 'created_at', 'preferred_course_id']),
        ]);
    }

    public function summary(?int $termId = null): array
    {
        $base = fn () => Applicant::when($termId, fn ($q) => $q->where('academic_term_id', $termId));

        $sectionsTotal = Section::when($termId, fn ($q) => $q->where('academic_term_id', $termId))->count();
        $sectionsFull = Section::when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->whereColumn('enrolled_count', '>=', 'capacity')->count();

        return [
            'total_applicants'   => $base()->count(),
            'total_enrolled'     => Enrollment::when($termId, fn ($q) => $q->where('academic_term_id', $termId))
                                        ->where('status', 'finalized')->count(),
            'total_courses'      => Course::where('is_active', true)->count(),
            'total_sections'     => $sectionsTotal,
            'pending_pre_reg'    => $base()->where('status', Applicant::STATUS_PRE_REGISTERED)->count(),
            'exam_scheduled'     => $base()->where('status', Applicant::STATUS_EXAM_SCHEDULED)->count(),
            'exam_completed'     => $base()->where('status', Applicant::STATUS_EXAM_COMPLETED)->count(),
            'verified'           => $base()->where('status', Applicant::STATUS_VERIFIED)->count(),
            'rejected'           => $base()->where('status', Applicant::STATUS_REJECTED)->count(),
            'sections_full'      => $sectionsFull,
            'new_today'          => $base()->whereDate('created_at', today())->count(),
            'new_this_week'      => $base()->where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    public function topCourses(Request $request): JsonResponse
    {
        [$termId] = $this->resolveTermFilter($request);

        $rows = Course::where('is_active', true)
            ->withCount(['applicants as applicant_count' => function ($q) use ($termId) {
                if ($termId) {
                    $q->where('academic_term_id', $termId);
                }
            }])
            ->orderByDesc('applicant_count')
            ->take(5)
            ->get();

        return response()->json([
            'labels' => $rows->pluck('code'),
            'data'   => $rows->pluck('applicant_count'),
        ]);
    }

    public function funnel(Request $request): JsonResponse
    {
        [$termId] = $this->resolveTermFilter($request);
        $base = fn () => Applicant::when($termId, fn ($q) => $q->where('academic_term_id', $termId));

        return response()->json([
            'labels' => ['Pre-Registered', 'Exam Scheduled', 'Exam Completed', 'Verified', 'Enrolled'],
            'data'   => [
                $base()->where('status', Applicant::STATUS_PRE_REGISTERED)->count(),
                $base()->where('status', Applicant::STATUS_EXAM_SCHEDULED)->count(),
                $base()->where('status', Applicant::STATUS_EXAM_COMPLETED)->count(),
                $base()->where('status', Applicant::STATUS_VERIFIED)->count(),
                $base()->where('status', Applicant::STATUS_ENROLLED)->count(),
            ],
        ]);
    }

    public function trends(Request $request): JsonResponse
    {
        [$termId] = $this->resolveTermFilter($request);

        $driver = DB::connection()->getDriverName();
        $fmt = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";
        $fmtEnrolled = $driver === 'sqlite'
            ? "strftime('%Y-%m', enrolled_at)"
            : "DATE_FORMAT(enrolled_at, '%Y-%m')";

        $applicants = Applicant::select(DB::raw("$fmt as ym"), DB::raw('COUNT(*) as c'))
            ->when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->groupBy('ym')->orderBy('ym')->pluck('c', 'ym');

        $enrolled = Enrollment::select(DB::raw("$fmtEnrolled as ym"), DB::raw('COUNT(*) as c'))
            ->when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->where('status', 'finalized')
            ->groupBy('ym')->orderBy('ym')->pluck('c', 'ym');

        $labels = collect($applicants->keys())->merge($enrolled->keys())->unique()->sort()->values();

        return response()->json([
            'labels'     => $labels,
            'applicants' => $labels->map(fn ($l) => (int) ($applicants[$l] ?? 0)),
            'enrolled'   => $labels->map(fn ($l) => (int) ($enrolled[$l] ?? 0)),
        ]);
    }

    public function courseCapacities(Request $request): JsonResponse
    {
        [$termId] = $this->resolveTermFilter($request);

        $rows = Course::where('is_active', true)
            ->withCount(['enrollments as enrolled_count' => function ($q) use ($termId) {
                $q->where('status', 'finalized');
                if ($termId) {
                    $q->where('academic_term_id', $termId);
                }
            }])
            ->orderBy('code')->get();

        return response()->json([
            'labels'   => $rows->pluck('code'),
            'quota'    => $rows->pluck('quota'),
            'enrolled' => $rows->pluck('enrolled_count'),
        ]);
    }

    public function demographics(Request $request): JsonResponse
    {
        [$termId] = $this->resolveTermFilter($request);

        $driver = DB::connection()->getDriverName();

        $gender = Applicant::select('gender', DB::raw('COUNT(*) as c'))
            ->when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->groupBy('gender')->pluck('c', 'gender');

        $bucketExpr = $driver === 'sqlite'
            ? "CASE
                WHEN (strftime('%Y','now') - strftime('%Y', birth_date)) < 17 THEN 'Below 17'
                WHEN (strftime('%Y','now') - strftime('%Y', birth_date)) BETWEEN 17 AND 19 THEN '17-19'
                WHEN (strftime('%Y','now') - strftime('%Y', birth_date)) BETWEEN 20 AND 22 THEN '20-22'
                ELSE '23+' END"
            : "CASE
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 17 THEN 'Below 17'
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 17 AND 19 THEN '17-19'
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 20 AND 22 THEN '20-22'
                ELSE '23+' END";

        $ages = Applicant::select(
            DB::raw("$bucketExpr as bucket"),
            DB::raw('COUNT(*) as c')
        )->when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->groupBy('bucket')->pluck('c', 'bucket');

        return response()->json([
            'gender' => [
                'labels' => $gender->keys(),
                'data'   => $gender->values(),
            ],
            'age' => [
                'labels' => $ages->keys(),
                'data'   => $ages->values(),
            ],
        ]);
    }

    private function resolveTermFilter(Request $request): array
    {
        $raw = (string) $request->query('term', 'active');

        if ($raw === 'all') {
            return [null, 'All Terms'];
        }

        if ($raw === 'active' || $raw === '') {
            $active = AcademicTerm::where('is_active', true)->latest('school_year')->first();
            return [$active?->id, $active ? ($active->school_year . ' ' . $active->semester) : 'No Active Term'];
        }

        $term = AcademicTerm::find((int) $raw);
        if (! $term) {
            $active = AcademicTerm::where('is_active', true)->latest('school_year')->first();
            return [$active?->id, $active ? ($active->school_year . ' ' . $active->semester) : 'No Active Term'];
        }

        return [$term->id, $term->school_year . ' ' . $term->semester];
    }
}
