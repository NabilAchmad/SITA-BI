<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeranDosenTa extends Model
{
    protected $table = 'peran_dosen_ta';

    protected $fillable = [
        'dosen_id',
        'tugas_akhir_id',
        'peran',
        'setuju_pembatalan',         // ✅ tambahkan ini
        'tanggal_verifikasi',        // ✅ dan ini
        'catatan_verifikasi'         // (opsional, kalau dipakai)
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class);
    }
}
