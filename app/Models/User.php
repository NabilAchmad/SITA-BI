<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read \App\Models\Mahasiswa|null $mahasiswa
 * @property-read \App\Models\Dosen|null $dosen
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read string $avatar_url
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo', // jika ada kolom ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================
    //        RELATIONSHIP
    // =========================

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    // =========================
    //       CUSTOM ACCESSORS
    // =========================

    public function getAvatarUrlAttribute(): string
    {
        return $this->photo && Storage::disk('public')->exists($this->photo)
            ? asset('storage/' . $this->photo)
            : asset('assets/img/default-user.png');
    }

    // =========================
    //         HELPERS
    // =========================

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('nama_role', $role);
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->pluck('nama_role')->intersect($roles)->isNotEmpty();
    }
}
