<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TugasAkhir extends Model
{
    use HasFactory;

    // Definisi konstanta status
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
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // --- RELASI DASAR ---
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function peranDosenTa(): HasMany
    {
        return $this->hasMany(PeranDosenTa::class);
    }

    public function revisiTa(): HasMany
    {
        return $this->hasMany(RevisiTa::class);
    }

    public function bimbinganTa(): HasMany
    {
        return $this->hasMany(BimbinganTA::class);
    }

    public function dokumenTa(): HasMany
    {
        return $this->hasMany(DokumenTa::class);
    }

    public function sidang(): HasMany
    {
        return $this->hasMany(Sidang::class);
    }

    /**
     * [PERBAIKAN] Mendefinisikan relasi ke satu data sidang yang paling baru.
     * Ini yang akan menyelesaikan eror Anda.
     */
    public function sidangTerakhir(): HasOne
    {
        // latestOfMany() akan secara otomatis mengambil record Sidang
        // dengan 'created_at' atau 'id' terbaru yang berelasi.
        return $this->hasOne(Sidang::class, 'tugas_akhir_id')->latestOfMany();
    }
    
    // --- ACCESSOR ---
    protected function pembimbingSatu(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Pastikan relasi sudah dimuat untuk efisiensi
                if (! $this->relationLoaded('peranDosenTa')) {
                    $this->load('peranDosenTa');
                }
                return $this->peranDosenTa->firstWhere('peran', PeranDosenTa::PERAN_PEMBIMBING_1);
            }
        );
    }

    protected function pembimbingDua(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->relationLoaded('peranDosenTa')) {
                    $this->load('peranDosenTa');
                }
                return $this->peranDosenTa->firstWhere('peran', PeranDosenTa::PERAN_PEMBIMBING_2);
            }
        );
    }

    public function disetujui_oleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function ditolak_oleh()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    // --- SCOPE ---
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

    /**
     * Scope untuk mengambil tugas akhir yang menunggu validasi.
     */
    public function scopeAwaitingValidation($query)
    {
        return $query->whereIn('status', [self::STATUS_DIAJUKAN]);
        // Jika revisi judul juga divalidasi di sini, tambahkan:
        // return $query->whereIn('status', [self::STATUS_DIAJUKAN, self::STATUS_REVISI]);
    }
}
