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
        'setuju_pembatalan',
        'tanggal_verifikasi',
        'catatan_verifikasi',
    ];

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    // Relasi ke Tugas Akhir
    public function tugasAkhir()
    // public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
}
