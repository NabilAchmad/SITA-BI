<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbinganTA extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_ta';

    /**
     * BARU: Konstanta untuk status bimbingan.
     * Menghilangkan penggunaan "magic strings" di seluruh aplikasi.
     */
    const STATUS_MENUNGGU  = 'menunggu';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK   = 'ditolak';
    const STATUS_SELESAI   = 'selesai'; // Jika ada status lain seperti ini

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

    // Relasi-relasi (sudah benar)

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function catatanBimbingan()
    {
        return $this->hasMany(CatatanBimbingan::class, 'bimbingan_ta_id');
    }

    public function historyPerubahan()
    {
        return $this->hasMany(HistoryPerubahanJadwal::class, 'bimbingan_ta_id');
    }

    // Relasi 'file' sepertinya tidak benar, karena hasOneThrough 
    // biasanya untuk mendapatkan satu model melalui model perantara.
    // Relasi ini mencoba mendapatkan Mahasiswa melalui TugasAkhir.
    // Jika tujuannya mendapatkan mahasiswa, relasi yang benar adalah:
    public function mahasiswa() {
        return $this->hasOneThrough(Mahasiswa::class, TugasAkhir::class, 'id', 'id', 'tugas_akhir_id', 'mahasiswa_id');
    }
}
