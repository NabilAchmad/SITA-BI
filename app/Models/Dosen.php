<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nidn'];
    protected $table = 'dosen';

    /**
     * Relasi ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke TawaranTopik.
     */
    public function topik(): HasMany
    {
        return $this->hasMany(TawaranTopik::class);
    }

    /**
     * Relasi ke BimbinganTA.
     */
    public function bimbingan(): HasMany
    {
        return $this->hasMany(BimbinganTa::class);
    }

    /**
     * Relasi ke PeranDosenTa.
     */
    public function peranDosenTa(): HasMany
    {
        return $this->hasMany(PeranDosenTa::class, 'dosen_id');
    }

    /**
     * Relasi ke NilaiSidang.
     */
    public function nilaiSidang(): HasMany
    {
        return $this->hasMany(NilaiSidang::class);
    }

    /**
     * Relasi ke ReviewDokumenTa.
     */
    public function reviewDokumen(): HasMany
    {
        return $this->hasMany(ReviewDokumenTa::class, 'reviewer_id');
    }

    /**
     * Relasi ke RevisiTa.
     */
    public function revisiTa(): HasMany
    {
        return $this->hasMany(RevisiTa::class);
    }

    /**
     * PENAMBAHAN KUNCI UNTUK REFACTORING.
     * Relasi ini berfungsi sebagai 'shortcut' untuk mendapatkan semua TugasAkhir
     * yang dibimbing oleh seorang dosen, melalui tabel perantara 'peran_dosen_ta'.
     * Ini membuat query di service menjadi jauh lebih bersih.
     */
    public function tugasAkhirBimbingan(): HasManyThrough
    {
        return $this->hasManyThrough(
            TugasAkhir::class,      // Model tujuan yang ingin kita akses
            PeranDosenTa::class,    // Model perantara
            'dosen_id',             // Foreign key di tabel perantara (peran_dosen_ta)
            'id',                   // Foreign key di tabel tujuan (tugas_akhir)
            'id',                   // Local key di tabel ini (dosen)
            'tugas_akhir_id'        // Local key di tabel perantara (peran_dosen_ta)
        );
    }

    /**
     * Mengecek apakah user sedang online berdasarkan cache.
     */
    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->id);
    }
}
