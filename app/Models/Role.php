<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['nama_role', 'deskripsi'];

    // Relasi ke pivot table user_roles
    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    // Relasi langsung ke users (banyak ke banyak)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
