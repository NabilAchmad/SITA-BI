<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany; // <-- Pastikan ini di-import

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = ['user_id', 'nim', 'prodi', 'angkatan', 'kelas'];

    /**
     * Relasi ke model User (Kunci Utama).
     * Ini sudah benar dan sangat penting.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Tugas Akhir yang aktif.
     */
    public function tugasAkhir(): HasOne
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id');
    }

    /**
     * ✅ [PERBAIKAN FINAL] Menambahkan relasi polimorfik ke CatatanBimbingan.
     * Ini memberitahu sistem bahwa seorang Mahasiswa bisa menjadi 'author'
     * dari banyak catatan bimbingan.
     */
    public function catatanBimbingan(): MorphMany
    {
        return $this->morphMany(CatatanBimbingan::class, 'author');
    }

    // --- Relasi-relasi Anda yang lain (sudah benar) ---

    public function peranDosenTA(): HasManyThrough
    {
        return $this->hasManyThrough(
            PeranDosenTA::class,
            TugasAkhir::class,
            'mahasiswa_id',     // Foreign key di TugasAkhir
            'tugas_akhir_id', // Foreign key di PeranDosenTA
            'id',             // Local key di Mahasiswa
            'id'              // Local key di TugasAkhir
        );
    }

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }


    public function notifikasi()
    {
        return $this->hasMany(NotifikasiTa::class);
    }

    public function sidang()
    {
        // Ganti 'foreign_key' dan 'local_key' sesuai dengan struktur tabel Anda
        return $this->hasMany(Sidang::class, 'tugas_akhir_id', 'id');
    }

    public function jadwalSidangSempro()
    {
        return $this->hasManyThrough(
            JadwalSidang::class,
            Sidang::class,
            'tugas_akhir_id', // Foreign key on Sidang table...
            'sidang_id', // Foreign key on JadwalSidang table...
            'id', // Local key on Mahasiswa table...
            'id'  // Local key on Sidang table...
        )->where('sidang.jenis_sidang', 'proposal');
    }

    public function jadwalSidangAkhir()
    {
        return $this->hasManyThrough(
            JadwalSidang::class,
            Sidang::class,
            'tugas_akhir_id',
            'sidang_id',
            'id',
            'id'
        )->where('sidang.jenis_sidang', 'akhir');
    }
}
