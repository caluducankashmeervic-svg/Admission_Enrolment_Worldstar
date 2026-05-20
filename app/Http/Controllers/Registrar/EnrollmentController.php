<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentRequest;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Verification;
use App\Services\CapacityEnforcer;
use App\Services\CorPdfGenerator;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function __construct(
        protected CapacityEnforcer $capacity,
        protected CorPdfGenerator  $cor,
    ) {}

    public function show(Applicant $applicant)
    {
        $applicant->load(['preferredCourse', 'academicTerm', 'verification', 'latestExamResult', 'enrollment']);

        $sections = Section::with('course')
            ->where('course_id', $applicant->preferred_course_id)
            ->where('academic_term_id', $applicant->academic_term_id)
            ->where('is_open', true)
            ->orderBy('name')
            ->get();

        return view('registrar.enrollment-finalize', compact('applicant', 'sections'));
    }

    public function finalize(EnrollmentRequest $request)
    {
        try {
            $enrollment = DB::transaction(function () use ($request) {
                /** @var Applicant $applicant */
                $applicant = Applicant::with('verification', 'latestExamResult')
                    ->lockForUpdate()
                    ->findOrFail($request->applicant_id);

                if ($applicant->status === Applicant::STATUS_ENROLLED) {
                    throw new \RuntimeException('Applicant is already enrolled.');
                }
                $verification = $applicant->verification;
                if (! $verification || $verification->status !== Verification::STATUS_VERIFIED) {
                    throw new \RuntimeException('Applicant documents are not fully verified.');
                }
                $exam = $applicant->latestExamResult;
                if (! $exam || $exam->result !== ExamResult::RESULT_PASSED) {
                    throw new \RuntimeException('Applicant has not passed the entrance exam.');
                }

                $course = Course::lockForUpdate()->findOrFail($request->course_id);
                $this->capacity->assertCourseQuota($course, $applicant->academic_term_id);

                if ($request->filled('section_id')) {
                    $section = Section::lockForUpdate()->findOrFail($request->section_id);
                    if ($section->course_id !== $course->id
                        || $section->academic_term_id !== $applicant->academic_term_id) {
                        throw new \RuntimeException('Section does not match course/term.');
                    }
                    $this->capacity->assertSectionCapacity($section);
                } else {
                    $section = $this->capacity->pickAvailableSection(
                        $course->id, $applicant->academic_term_id, 1
                    );
                    if (! $section) {
                        throw new \RuntimeException('No available section for this course.');
                    }
                }

                $enrollmentNo = $this->nextEnrollmentNo($applicant->academic_term_id);

                $enrollment = Enrollment::create([
                    'enrollment_no'    => $enrollmentNo,
                    'applicant_id'     => $applicant->id,
                    'course_id'        => $course->id,
                    'section_id'       => $section->id,
                    'academic_term_id' => $applicant->academic_term_id,
                    'processed_by'     => $request->user()->id,
                    'status'           => Enrollment::STATUS_FINALIZED,
                    'enrolled_at'      => now(),
                ]);

                $section->increment('enrolled_count');
                $applicant->update(['status' => Applicant::STATUS_ENROLLED]);

                $path = $this->cor->generate($enrollment);
                $enrollment->update(['cor_path' => $path]);

                AuditLog::record('enrollment.finalize', $enrollment, [
                    'enrollment_no' => $enrollment->enrollment_no,
                    'section'       => $section->name,
                ]);

                return $enrollment;
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['enrollment' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('enrollment.cor.download', $enrollment->id)
            ->with('status', "Enrollment finalized: {$enrollment->enrollment_no}");
    }

    protected function nextEnrollmentNo(int $termId): string
    {
        $year  = date('Y');
        $count = Enrollment::where('academic_term_id', $termId)->count() + 1;
        return sprintf('ENR-%s-%06d', $year, $count);
    }
}
