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

<<<<<<< HEAD
    public function topik()
=======

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }


    public function topik(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
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

    public function peranDosen()
    {
        return $this->hasMany(PeranDosenTA::class);
    }
}
