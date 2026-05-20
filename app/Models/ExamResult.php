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

    public const SMS_QUEUED   = 'queued';
    public const SMS_SENT     = 'sent';
    public const SMS_FAILED   = 'failed';
    public const SMS_NOT_SENT = 'not_sent';

    protected $fillable = [
        'applicant_id', 'exam_schedule_id',
        'score', 'percentile', 'result',
        'sms_status', 'sms_sent_at', 'sms_provider_ref',
    ];

    protected $casts = [
        'score'       => 'decimal:2',
        'percentile'  => 'decimal:2',
        'sms_sent_at' => 'datetime',
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
