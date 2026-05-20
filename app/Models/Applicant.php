<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Applicant extends Model
{
    use HasFactory;

    public const STATUS_PRE_REGISTERED = 'pre_registered';
    public const STATUS_EXAM_SCHEDULED = 'exam_scheduled';
    public const STATUS_EXAM_COMPLETED = 'exam_completed';
    public const STATUS_VERIFIED       = 'verified';
    public const STATUS_ENROLLED       = 'enrolled';
    public const STATUS_REJECTED       = 'rejected';

    protected $fillable = [
        'reference_code', 'user_id', 'preferred_course_id', 'academic_term_id',
        'first_name', 'middle_name', 'last_name', 'suffix',
        'gender', 'birth_date', 'civil_status', 'nationality', 'religion',
        'email', 'mobile', 'address_line', 'city', 'province', 'zip',
        'last_school_attended', 'last_school_address', 'strand_track',
        'year_graduated', 'gwa',
        'guardian_name', 'guardian_relationship', 'guardian_contact',
        'status',
    ];

    protected $casts = [
        'birth_date'     => 'date',
        'year_graduated' => 'integer',
        'gwa'            => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferredCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'preferred_course_id');
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function latestExamResult(): HasOne
    {
        return $this->hasOne(ExamResult::class)->latestOfMany();
    }

    public function verification(): HasOne
    {
        return $this->hasOne(Verification::class);
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name, $this->middle_name, $this->last_name, $this->suffix,
        ])));
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function scopeStatus($q, string $status) { return $q->where('status', $status); }
    public function scopeForTerm($q, int $termId)   { return $q->where('academic_term_id', $termId); }
}
