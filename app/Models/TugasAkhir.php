<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    use HasFactory;

    // 1. DEFINISIKAN SEMUA STATUS SEBAGAI KONSTANTA
    // Ini menghilangkan 'magic strings' dan mencegah typo.
    const STATUS_DRAFT = 'draft';
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_REVISI = 'revisi';
    const STATUS_MENUNGGU_PEMBATALAN = 'menunggu_pembatalan';
    const STATUS_DIBATALKAN = 'dibatalkan';
    const STATUS_LULUS_TANPA_REVISI = 'lulus_tanpa_revisi';
    const STATUS_LULUS_DENGAN_REVISI = 'lulus_dengan_revisi';
    const STATUS_SELESAI = 'selesai';

    protected $table = 'tugas_akhir';
    protected $guarded = ['id'];

    // 2. LENGKAPI TIPE DATA CASTS
    // Pastikan semua kolom tanggal menjadi objek Carbon.
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

    public function bimbinganTa()
    {
        return $this->hasMany(BimbinganTA::class);
    }

    public function revisiTa()
    {
        return $this->hasMany(RevisiTa::class);
    }

    public function dokumenTa()
    {
        return $this->hasMany(DokumenTa::class);
    }

    public function sidang()
    {
        return $this->hasMany(Sidang::class);
    }

    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTa::class);
    }

    // 3. TAMBAHKAN RELASI SPESIFIK UNTUK PEMBIMBING
    // Membuat pengambilan data pembimbing menjadi super mudah.
    protected function pembimbingSatu(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->peranDosenTa->firstWhere('peran', PeranDosenTa::PERAN_PEMBIMBING_1)
        );
    }

    // Atribut ini akan mencari pembimbing 2 dari relasi peranDosenTa
    protected function pembimbingDua(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->peranDosenTa->firstWhere('peran', PeranDosenTa::PERAN_PEMBIMBING_2)
        );
    }

    // 4. IMPLEMENTASI QUERY SCOPE UNTUK TA AKTIF
    // Sederhanakan query yang berulang di banyak tempat.
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            self::STATUS_DIBATALKAN,
            self::STATUS_LULUS_DENGAN_REVISI,
            self::STATUS_LULUS_TANPA_REVISI,
            self::STATUS_SELESAI,
        ]);
    }

    // 5. PINDAHKAN LOGIKA PROGRESS KE ACCESSOR
    // Controller tidak perlu tahu cara menghitung ini.
    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Eager load relasi jika belum ada untuk efisiensi
                if (! $this->relationLoaded('bimbinganTa')) {
                    $this->load('bimbinganTa');
                }
                $jumlahBimbingan = $this->bimbinganTa->count();

                return match ($this->status) {
                    self::STATUS_DIAJUKAN, self::STATUS_REVISI => min(ceil(($jumlahBimbingan / 8) * 50), 49), // 0-49%
                    self::STATUS_DISETUJUI => 50 + min(ceil(($jumlahBimbingan / 8) * 50), 49), // 50-99%
                    self::STATUS_LULUS_DENGAN_REVISI, self::STATUS_LULUS_TANPA_REVISI, self::STATUS_SELESAI => 100,
                    default => 0,
                };
            }
        );
    }
}
