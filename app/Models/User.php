<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN     = 'admin';
    public const ROLE_REGISTRAR = 'registrar';
    public const ROLE_APPLICANT = 'applicant';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active',
        'username', 'firstname', 'lastname', 'middlename',
        'contact_no', 'student_no', 'profile_photo_path',
    ];

    protected $appends = ['profile_photo_url'];

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            $diskName = config('filesystems.profile_disk', 'public');
            if ($diskName === 'public') {
                // Use root-relative URL so it works regardless of APP_URL / port.
                // Only return the storage path when the file actually exists.
                if (Storage::disk('public')->exists($this->profile_photo_path)) {
                    return '/storage/' . ltrim($this->profile_photo_path, '/');
                }
            } else {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk($diskName);
                if ($disk->exists($this->profile_photo_path)) {
                    return $disk->url($this->profile_photo_path);
                }
            }
        }
        $seed = urlencode($this->name ?: $this->email ?: 'user');
        return "https://ui-avatars.com/api/?name={$seed}&background=0D8ABC&color=fff&size=128";
    }

    public function getDisplayNameAttribute(): string
    {
        $full = trim(implode(' ', array_filter([$this->firstname, $this->middlename, $this->lastname])));
        return $full !== '' ? $full : $this->name;
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function applicant(): HasOne
    {
        return $this->hasOne(Applicant::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class, 'registrar_id');
    }

    public function enrollmentsProcessed(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'processed_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdmin(): bool     { return $this->role === self::ROLE_ADMIN; }
    public function isRegistrar(): bool { return $this->role === self::ROLE_REGISTRAR; }
    public function isApplicant(): bool { return $this->role === self::ROLE_APPLICANT; }
}
