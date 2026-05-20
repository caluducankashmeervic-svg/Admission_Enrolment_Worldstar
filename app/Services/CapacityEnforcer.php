<?php

namespace App\Services;

use App\Models\Course;
use App\Models\ExamSchedule;
use App\Models\Section;
use RuntimeException;

class CapacityEnforcer
{
    public function assertCourseQuota(Course $course, ?int $termId = null): void
    {
        if (! $course->is_active) {
            throw new RuntimeException("Course {$course->code} is not active.");
        }
        if (! $course->hasQuotaAvailable($termId)) {
            throw new RuntimeException(
                "Course quota reached for {$course->code} ({$course->quota} slots filled)."
            );
        }
    }

    public function assertSectionCapacity(Section $section): void
    {
        if (! $section->is_open) {
            throw new RuntimeException("Section {$section->name} is closed.");
        }
        if (! $section->hasSlot()) {
            throw new RuntimeException(
                "Section {$section->name} is full ({$section->enrolled_count}/{$section->capacity})."
            );
        }
    }

    public function assertExamScheduleCapacity(ExamSchedule $schedule): void
    {
        if (! $schedule->hasSlot()) {
            throw new RuntimeException("Exam batch {$schedule->batch_code} is full.");
        }
    }

    public function pickAvailableSection(int $courseId, int $termId, int $yearLevel = 1): ?Section
    {
        return Section::where('course_id', $courseId)
            ->where('academic_term_id', $termId)
            ->where('year_level', $yearLevel)
            ->where('is_open', true)
            ->whereColumn('enrolled_count', '<', 'capacity')
            ->orderBy('enrolled_count')
            ->lockForUpdate()
            ->first();
    }
}
