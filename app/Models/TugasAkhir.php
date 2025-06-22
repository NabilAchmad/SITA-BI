<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TugasAkhir extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'status',
        'tanggal_pengajuan',
        'file_path',
        'similarity_score',
        'alasan_pembatalan',
        'alasan_penolakan',
        'terakhir_dicek',
    ];

    /** Relasi ke Mahasiswa */
    // public function mahasiswa(): BelongsTo
    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /** Relasi ke semua peran dosen TA (pembimbing/penguji) */
    // public function peranDosenTa(): HasMany
    // Relasi ke Dosen Pembimbing/Penguji via peran_dosen_ta
    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke dosen pembimbing 1 */
    public function pembimbing1(): HasOne
    {
        return $this->hasOne(PeranDosenTa::class, 'tugas_akhir_id')->where('peran', 'pembimbing1');
    }

    /** Relasi ke dosen pembimbing 2 */
    public function pembimbing2(): HasOne
    {
        return $this->hasOne(PeranDosenTa::class, 'tugas_akhir_id')->where('peran', 'pembimbing2');
    }

    /** Relasi ke bimbingan */
    // public function bimbingan(): HasMany
    // Relasi ke Bimbingan TA
    public function bimbingan()
    {
        return $this->hasMany(BimbinganTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke dokumen TA */
    // public function dokumen(): HasMany
    // Relasi ke Dokumen TA (proposal, draft, final)
    public function dokumen()
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke revisi */
    // public function revisi(): HasMany
    // Relasi ke Revisi TA
    public function revisi()
    {
        return $this->hasMany(RevisiTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke sidang */
    // public function sidang(): HasOne
    // public function sidang()
    // Relasi ke Sidang TA (bisa juga hasMany jika ada banyak sidang)
    public function sidang()
    {
        return $this->hasMany(Sidang::class);
        // return $this->hasOne(Sidang::class, 'tugas_akhir_id');
    }

    /** Relasi ke notifikasi TA */
    // public function notifikasi(): HasMany
    // Relasi ke Notifikasi TA
    public function notifikasi()
    {
        return $this->hasMany(NotifikasiTa::class, 'tugas_akhir_id');
    }

    // Jika kamu menambahkan kolom tawaran_topik_id nanti, bisa aktifkan ini kembali
    // public function tawaranTopik(): BelongsTo
    public function dosenPembimbing()
    {
        return $this->hasMany(PeranDosenTA::class);
    }

    // relasi banyak ke PeranDosenTA
    public function peranDosen()
    {
        return $this->hasMany(PeranDosenTA::class, 'tugas_akhir_id');
    }

    // relasi 1 ke PeranDosenTA yang peran pembimbing1
    // public function pembimbing1()
    // {
    //     return $this->hasOne(PeranDosenTA::class, 'tugas_akhir_id')->where('peran', 'pembimbing1');
    // }

    // // relasi 1 ke PeranDosenTA yang peran pembimbing2
    // public function pembimbing2()
    // {
    //     return $this->hasOne(PeranDosenTA::class, 'tugas_akhir_id')->where('peran', 'pembimbing2');
    // }

    public function sidangTerakhir()
    {
        return $this->hasOne(Sidang::class)
            ->whereIn('jenis_sidang', ['akhir', 'proposal'])
            ->latestOfMany();
    }
}
