<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BimbinganTA extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_ta';

    /**
     * Konstanta untuk status bimbingan agar kode lebih bersih.
     */
    const STATUS_DIJADWALKAN = 'dijadwalkan'; // Nama yang lebih baik dari 'disetujui' untuk jadwal baru
    const STATUS_DITOLAK     = 'ditolak';
    const STATUS_SELESAI     = 'selesai';
    const STATUS_BERJALAN    = 'berjalan'; // Status sementara untuk sesi yang aktif tapi belum selesai

    /**
     * ✅ PERBAIKAN: Kolom 'catatan' dihapus karena tidak ada di tabel ini.
     */
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'peran',
        'sesi_ke',
        'tanggal_bimbingan',
        'jam_bimbingan',
        'status_bimbingan',
    ];

    /**
     * Casts untuk memastikan kolom tanggal di-handle sebagai objek Carbon.
     */
    protected $casts = [
        'tanggal_bimbingan' => 'date',
    ];


    // --- RELASI-RELASI ---

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * ✅ PERBAIKAN: Nama relasi diubah menjadi 'catatan' agar selaras dengan Service.
     * Satu sesi bimbingan dapat memiliki banyak catatan.
     */
    public function catatan(): HasMany
    {
        return $this->hasMany(CatatanBimbingan::class, 'bimbingan_ta_id');
    }

    public function historyPerubahan(): HasMany
    {
        return $this->hasMany(HistoryPerubahanJadwal::class, 'bimbingan_ta_id');
    }

    /**
     * Relasi untuk mendapatkan mahasiswa secara langsung melalui TugasAkhir.
     * (Implementasi Anda sudah sangat baik dan benar).
     */
    public function mahasiswa()
    {
        return $this->hasOneThrough(Mahasiswa::class, TugasAkhir::class, 'id', 'id', 'tugas_akhir_id', 'mahasiswa_id');
    }
}
