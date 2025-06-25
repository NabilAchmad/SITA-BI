<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbinganTA extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_ta';

    // Tambahkan ini
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'peran',
        'sesi_ke',
        'tanggal_bimbingan',
        'jam_bimbingan',
        'catatan',
        'status_bimbingan',
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function file()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            TugasAkhir::class,
            'id',
            'id',
            'tugas_akhir_id',
            'mahasiswa_id'
        );
    }

    public function catatanBimbingan()
    {
        return $this->hasMany(CatatanBimbingan::class, 'bimbingan_ta_id');
    }
}
