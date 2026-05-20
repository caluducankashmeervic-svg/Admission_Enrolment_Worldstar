<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verification extends Model
{
    use HasFactory;

    public const STATUS_PENDING    = 'pending';
    public const STATUS_INCOMPLETE = 'incomplete';
    public const STATUS_VERIFIED   = 'verified';
    public const STATUS_REJECTED   = 'rejected';

    public const REQUIRED_DOCS = [
        'doc_form_137', 'doc_psa_birth_cert', 'doc_good_moral',
        'doc_id_photos', 'doc_medical_cert', 'doc_diploma',
    ];

    protected $fillable = [
        'applicant_id', 'registrar_id',
        'doc_form_137', 'doc_psa_birth_cert', 'doc_good_moral',
        'doc_id_photos', 'doc_medical_cert', 'doc_diploma',
        'status', 'remarks', 'verified_at',
    ];

    protected $casts = [
        'doc_form_137'       => 'boolean',
        'doc_psa_birth_cert' => 'boolean',
        'doc_good_moral'     => 'boolean',
        'doc_id_photos'      => 'boolean',
        'doc_medical_cert'   => 'boolean',
        'doc_diploma'        => 'boolean',
        'verified_at'        => 'datetime',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrar_id');
    }

    public function allDocumentsComplete(): bool
    {
        foreach (self::REQUIRED_DOCS as $doc) {
            if (! $this->{$doc}) return false;
        }
        return true;
    }
}
