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
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /** Relasi ke semua peran dosen TA (pembimbing/penguji) */
    public function peranDosenTa(): HasMany
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
    public function bimbingan(): HasMany
    {
        return $this->hasMany(BimbinganTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke dokumen TA */
    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke revisi */
    public function revisi(): HasMany
    {
        return $this->hasMany(RevisiTa::class, 'tugas_akhir_id');
    }

    /** Relasi ke sidang */
    public function sidang(): HasOne
    {
        return $this->hasOne(Sidang::class, 'tugas_akhir_id');
    }

    /** Relasi ke notifikasi TA */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(NotifikasiTa::class, 'tugas_akhir_id');
    }

    // Jika kamu menambahkan kolom tawaran_topik_id nanti, bisa aktifkan ini kembali
    public function tawaranTopik(): BelongsTo
    {
        return $this->belongsTo(TawaranTopik::class, 'tawaran_topik_id');
    }
}
