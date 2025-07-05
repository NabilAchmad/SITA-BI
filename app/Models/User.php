<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo', // Menambahkan 'photo' agar bisa diisi saat update profil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    // public function getProdi(){
    //     return $this->hasOne(Mahasiswa::class, 'prodi');
    // }

    /**
     * Memeriksa apakah pengguna memiliki peran tertentu.
     *
     * @param string $roleName Nama peran yang ingin diperiksa.
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('nama_role', $roleName)->exists();
    }

    /**
     * ====================================================================
     * PERBAIKAN: Tambahkan metode ini agar RoleMiddleware berfungsi.
     * ====================================================================
     * Memeriksa apakah pengguna memiliki salah satu dari peran yang diberikan.
     *
     * @param array $roles Array dari nama-nama peran.
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('nama_role', $roles)->exists();
    }
}
