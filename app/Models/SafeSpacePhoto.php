<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeSpacePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'event_at',
        'is_active',
    ];

    protected $casts = [
        'event_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
