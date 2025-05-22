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
        'alasan_pembatalan',
        'tanggal_pengajuan',
        'file_path',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke Dosen Pembimbing/Penguji via peran_dosen_ta
    public function peranDosenTa(): HasMany
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Bimbingan TA
    public function bimbingan(): HasMany
    {
        return $this->hasMany(BimbinganTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Dokumen TA (proposal, draft, final)
    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Revisi TA
    public function revisi(): HasMany
    {
        return $this->hasMany(RevisiTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Sidang TA (bisa juga hasMany jika ada banyak sidang)
    public function sidang(): HasOne
    {
        return $this->hasOne(Sidang::class, 'tugas_akhir_id');
    }

    // Relasi ke Notifikasi TA
    public function notifikasi(): HasMany
    {
        return $this->hasMany(NotifikasiTa::class, 'tugas_akhir_id');
    }
}
