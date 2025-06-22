<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'nidn'];
    protected $table = 'dosen'; // Ini wajib untuk hindari pluralisasi salah

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function topik(): HasMany
    {
        return $this->hasMany(TawaranTopik::class);
    }

    public function bimbingan()
    {
        return $this->hasMany(BimbinganTa::class);
    }

    public function peranTa()
    {
        return $this->hasMany(PeranDosenTa::class);
    }

    public function nilaiSidang()
    {
        return $this->hasMany(NilaiSidang::class);
    }

    public function reviewDokumen()
    {
        return $this->hasMany(ReviewDokumenTa::class, 'reviewer_id');
    }

    public function revisiTa()
    {
        return $this->hasMany(RevisiTa::class);
    }

    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTA::class);
    }
    public function permintaanPembatalan()
    {
        return $this->peranDosenTa()
            ->whereNull('setuju_pembatalan')
            ->whereHas('tugasAkhir', function ($query) {
                $query->where('status', 'menunggu_pembatalan');
            });
    }
}
