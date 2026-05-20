<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'academic_term_id', 'name', 'year_level',
        'capacity', 'enrolled_count', 'is_open',
    ];

    protected $casts = [
        'year_level'     => 'integer',
        'capacity'       => 'integer',
        'enrolled_count' => 'integer',
        'is_open'        => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function hasSlot(): bool
    {
        return $this->is_open && $this->enrolled_count < $this->capacity;
    }

    public function remainingSlots(): int
    {
        return max(0, $this->capacity - $this->enrolled_count);
    }

    public function utilizationPercent(): float
    {
        return $this->capacity > 0
            ? round(($this->enrolled_count / $this->capacity) * 100, 2)
            : 0.0;
    }

    public function scopeOpen($q)               { return $q->where('is_open', true); }
    public function scopeForCourse($q, int $id) { return $q->where('course_id', $id); }
}
