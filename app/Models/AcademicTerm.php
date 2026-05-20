<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year', 'semester', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function sections(): HasMany      { return $this->hasMany(Section::class); }
    public function applicants(): HasMany    { return $this->hasMany(Applicant::class); }
    public function enrollments(): HasMany   { return $this->hasMany(Enrollment::class); }
    public function examSchedules(): HasMany { return $this->hasMany(ExamSchedule::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function getLabelAttribute(): string
    {
        return "{$this->school_year} — {$this->semester} Sem";
    }
}
