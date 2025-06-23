<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbinganTA extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_ta';

    // Tambahkan properti fillable untuk mendukung mass assignment
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'tanggal_bimbingan',
        'jam_bimbingan', // pastikan ini ad
        'catatan',
        'status_bimbingan',
        'file_id',
    ];

    // Relasi ke Tugas Akhir
    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    // Relasi ke Mahasiswa (tidak wajib jika bisa diakses via Tugas Akhir)
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            TugasAkhir::class,
            'id',              // tugas_akhir.id
            'id',              // mahasiswa.id
            'tugas_akhir_id',  // bimbingan_ta.tugas_akhir_id
            'mahasiswa_id'     // tugas_akhir.mahasiswa_id
        );
    }

    // Relasi ke Catatan Bimbingan
    public function catatanBimbingan()
    {
        return $this->hasMany(CatatanBimbingan::class, 'bimbingan_ta_id');
    }
}
