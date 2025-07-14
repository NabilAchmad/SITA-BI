<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute; // Import Attribute class
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// --- PASTIKAN SEMUA MODEL YANG DIPERLUKAN TELAH DI-IMPORT ---
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use App\Models\PeranDosenTa;
use App\Models\BimbinganTA;
use App\Models\DokumenTa;
use App\Models\Sidang;
use App\Models\TawaranTopik;

class TugasAkhir extends Model
{
    use HasFactory;

    // --- Definisi Konstanta dan Properti (Tidak Berubah) ---
    const STATUS_DRAFT = 'draft';
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_REVISI = 'revisi';
    const STATUS_MENUNGGU_PEMBATALAN = 'menunggu_pembatalan';
    const STATUS_DIBATALKAN = 'dibatalkan';
    const STATUS_LULUS_TANPA_REVISI = 'lulus_tanpa_revisi';
    const STATUS_LULUS_DENGAN_REVISI = 'lulus_dengan_revisi';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DITOLAK = 'ditolak';

    protected $table = 'tugas_akhir';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'tanggal_ditolak' => 'datetime',
    ];

    // --- Relasi Dasar (Tidak Berubah) ---
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
    public function bimbinganTa(): HasMany
    {
        return $this->hasMany(BimbinganTA::class);
    }
    public function dokumenTa(): HasMany
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }
    public function sidang(): HasMany
    {
        return $this->hasMany(Sidang::class);
    }
    public function tawaranTopik()
    {
        return $this->belongsTo(TawaranTopik::class, 'tawaran_topik_id');
    }
    public function sidangTerakhir(): HasOne
    {
        return $this->hasOne(Sidang::class, 'tugas_akhir_id')->latestOfMany();
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
    public function rejecter()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    // ======================================================================
    // PERBAIKAN UTAMA: Menggunakan BelongsToMany + Accessor
    // ======================================================================

    /**
     * Relasi dasar Many-to-Many ke Dosen melalui tabel pivot.
     * Ini menjadi FONDASI untuk mengambil data pembimbing.
     */
    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

    public function dosenPembimbing()
    {
        return $this->belongsToMany(Dosen::class, 'peran_dosen_ta', 'tugas_akhir_id', 'dosen_id')
            ->withPivot('peran')
            ->withTimestamps();
    }

    /**
     * ✅ PERBAIKAN: Menggunakan ACCESSOR untuk mendapatkan Pembimbing 1.
     * Jauh lebih bersih, efisien, dan anti-error.
     * Anda bisa memanggilnya seperti properti biasa: $tugasAkhir->pembimbing_satu
     */
    protected function pembimbingSatu(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->dosenPembimbing->where('pivot.peran', 'pembimbing1')->first()
        );
    }

    /**
     * ✅ PERBAIKAN: Menggunakan ACCESSOR untuk mendapatkan Pembimbing 2.
     * Anda bisa memanggilnya seperti properti biasa: $tugasAkhir->pembimbing_dua
     */
    protected function pembimbingDua(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->dosenPembimbing->where('pivot.peran', 'pembimbing2')->first()
        );
    }


    // --- Query Scopes (Tidak Berubah) ---
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            self::STATUS_DIBATALKAN,
            self::STATUS_DITOLAK,
            self::STATUS_LULUS_DENGAN_REVISI,
            self::STATUS_LULUS_TANPA_REVISI,
            self::STATUS_SELESAI,
        ]);
    }

    public function scopeAwaitingValidation($query)
    {
        return $query->whereIn('status', [self::STATUS_DIAJUKAN]);
    }
}
