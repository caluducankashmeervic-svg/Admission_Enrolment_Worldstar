<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'department', 'quota', 'duration_years', 'is_active',
    ];

    protected $casts = [
        'quota'          => 'integer',
        'duration_years' => 'integer',
        'is_active'      => 'boolean',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class, 'preferred_course_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCount(?int $termId = null): int
    {
        return $this->enrollments()
            ->when($termId, fn ($q) => $q->where('academic_term_id', $termId))
            ->where('status', 'finalized')
            ->count();
    }

    public function hasQuotaAvailable(?int $termId = null): bool
    {
        if ($this->quota <= 0) return true;
        return $this->enrolledCount($termId) < $this->quota;
    }
}
