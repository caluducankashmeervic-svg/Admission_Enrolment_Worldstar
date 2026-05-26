<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;

    public const RESULT_PENDING = 'pending';
    public const RESULT_PASSED  = 'passed';
    public const RESULT_FAILED  = 'failed';

    protected $fillable = [
        'applicant_id', 'exam_schedule_id',
        'score', 'percentile', 'result',
    ];

    protected $casts = [
        'score'       => 'decimal:2',
        'percentile'  => 'decimal:2',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function examSchedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class);
    }
}
