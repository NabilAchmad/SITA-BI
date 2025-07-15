<?php

// app/Models/PendaftaranSidang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranSidang extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_sidang';

    protected $fillable = [
        'tugas_akhir_id',
        'status_verifikasi',
        'status_pembimbing_1', // <-- Tambahkan ini
        'status_pembimbing_2', // <-- Tambahkan ini
        'file_skripsi_final',
        'file_cek_plagiarisme',
        'file_transkrip',
        'catatan_admin',
        'catatan_pembimbing_1', // <-- Tambahkan ini
        'catatan_pembimbing_2', // <-- Tambahkan ini
    ];

    // ... relasi lainnya
}