<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Cache;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nidn'];
    protected $table = 'dosen';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topik(): HasMany
    {
        return $this->hasMany(TawaranTopik::class, 'user_id', 'user_id');
    }

    // ... sisa relasi dan method lainnya tidak perlu diubah ...
    public function bimbingan(): HasMany
    {
        return $this->hasMany(BimbinganTa::class);
    }

    public function peranDosenTa(): HasMany
    {
        return $this->hasMany(PeranDosenTa::class, 'dosen_id');
    }

    public function tugasAkhirBimbingan(): HasManyThrough
    {
        return $this->hasManyThrough(
            TugasAkhir::class,
            PeranDosenTa::class,
            'dosen_id',
            'id',
            'id',
            'tugas_akhir_id'
        );
    }

    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->id);
    }
}
