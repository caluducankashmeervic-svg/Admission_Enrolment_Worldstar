<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_term_id', 'batch_code', 'exam_datetime',
        'venue', 'capacity', 'assigned_count', 'remarks',
    ];

    protected $casts = [
        'exam_datetime'  => 'datetime',
        'capacity'       => 'integer',
        'assigned_count' => 'integer',
    ];

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function hasSlot(): bool
    {
        return $this->assigned_count < $this->capacity;
    }

    public function remainingSlots(): int
    {
        return max(0, $this->capacity - $this->assigned_count);
    }
}
