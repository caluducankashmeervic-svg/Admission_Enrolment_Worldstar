<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'entity_type', 'entity_id', 'meta', 'ip_address',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?Model $entity = null, array $meta = []): self
    {
        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id'   => $entity?->getKey(),
            'meta'        => $meta,
            'ip_address'  => request()?->ip(),
        ]);
    }
}
