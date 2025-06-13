<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sidang extends Model
{
    use SoftDeletes;

    protected $table = 'sidang';

    protected $fillable = ['tugas_akhir_id', 'jenis_sidang', 'status'];

    /**
     * Relasi ke Tugas Akhir
     */
    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    /**
     * Relasi ke Jadwal Sidang
     */
    public function jadwalSidang(): HasOne
    {
        return $this->hasOne(JadwalSidang::class);
    }

    /**
     * Relasi ke Nilai Sidang
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(NilaiSidang::class);
    }

    /**
     * Relasi ke Penilaian Sidang (untuk eager loading penilaians)
     */
    public function penilaians(): HasMany
    {
        return $this->hasMany(NilaiSidang::class, 'sidang_id');
    }

    /**
     * Relasi ke Berita Acara Pasca Sidang
     */
    public function beritaAcaraPasca(): HasMany
    {
        return $this->hasMany(BeritaAcaraPascaSidang::class);
    }

    /**
     * Relasi ke Berita Acara Pra Sidang
     */
    public function beritaAcaraPra(): HasMany
    {
        return $this->hasMany(BeritaAcaraPraSidang::class);
    }

    /**
     * Relasi tidak langsung ke Mahasiswa melalui Tugas Akhir
     * (Eager loading compatible)
     */
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            TugasAkhir::class,
            'id',              // Foreign key on TugasAkhir table...
            'id',              // Foreign key on Mahasiswa table...
            'tugas_akhir_id',  // Local key on Sidang table...
            'mahasiswa_id'     // Local key on TugasAkhir table...
        );
    }

    /**
     * Menghitung rata-rata nilai sidang
     */
    public function rataRataNilai()
    {
        return $this->nilai()->avg('skor');
    }

    /**
     * Menentukan status kelulusan sidang berdasarkan rata-rata nilai
     */
    public function statusKelulusan()
    {
        $rataRata = $this->rataRataNilai();
        if ($rataRata === null) {
            return 'Belum Dinilai';
        }
        return $rataRata >= 60 ? 'Lulus' : 'Tidak Lulus';
    }
}