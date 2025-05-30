<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSidang extends Model
{
    protected $table = 'nilai_sidang';

    protected $fillable = [
        'sidang_id', 'dosen_id', 'aspek', 'komentar', 'skor'
    ];

    public function sidang(): BelongsTo
    {
        return $this->belongsTo(Sidang::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function nilai()
    {
        return $this->hasMany(NilaiSidang::class, 'sidang_id');
    }

    public function rataRataNilai()
    {
        return $this->nilai()->avg('skor');
    }

    public function statusKelulusan()
    {
        $avg = $this->rataRataNilai();
        if ($avg === null) return 'Belum Dinilai';
        return $avg >= 60 ? 'Lulus' : 'Tidak Lulus';
    }

    public function nilaiSidang()
    {
        return $this->hasMany(NilaiSidang::class, 'dosen_id');
    }

}
