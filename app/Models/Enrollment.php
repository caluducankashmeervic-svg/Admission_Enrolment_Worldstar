<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    public const STATUS_FINALIZED = 'finalized';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'enrollment_no', 'applicant_id', 'course_id', 'section_id',
        'academic_term_id', 'processed_by', 'status', 'cor_path', 'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public function applicant(): BelongsTo    { return $this->belongsTo(Applicant::class); }
    public function course(): BelongsTo       { return $this->belongsTo(Course::class); }
    public function section(): BelongsTo      { return $this->belongsTo(Section::class); }
    public function academicTerm(): BelongsTo { return $this->belongsTo(AcademicTerm::class); }
    public function processor(): BelongsTo    { return $this->belongsTo(User::class, 'processed_by'); }

    public function scopeFinalized($q) { return $q->where('status', self::STATUS_FINALIZED); }
}
