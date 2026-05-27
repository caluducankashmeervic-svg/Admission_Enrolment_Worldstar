<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\Course;
use App\Models\Enrollment;
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
        ]);
    }

    public function summary(?int $termId = null): array
    {
        return [
            'total_applicants' => Applicant::when($termId, fn ($q) => $q->where('academic_term_id', $termId))->count(),
            'total_enrolled'   => Enrollment::when($termId, fn ($q) => $q->where('academic_term_id', $termId))
                                    ->where('status', 'finalized')->count(),
            'total_courses'    => Course::where('is_active', true)->count(),
            'total_sections'   => Section::when($termId, fn ($q) => $q->where('academic_term_id', $termId))->count(),
        ];
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
