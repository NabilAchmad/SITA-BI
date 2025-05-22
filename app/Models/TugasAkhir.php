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

<<<<<<< HEAD
    protected $table = 'tugas_akhir';  // sesuaikan dengan nama tabel di DB
=======
    protected $table = 'tugas_akhir';
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'status',
<<<<<<< HEAD
        'tanggal_pengajuan'
    ];

    public function mahasiswa()
=======
        'alasan_pembatalan',
        'tanggal_pengajuan',
        'file_path',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa(): BelongsTo
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->belongsTo(Mahasiswa::class);
    }

<<<<<<< HEAD

    public function peranDosenTa()
=======
    // Relasi ke Dosen Pembimbing/Penguji via peran_dosen_ta
    public function peranDosenTa(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

<<<<<<< HEAD
    public function bimbingan()
=======
    // Relasi ke Bimbingan TA
    public function bimbingan(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(BimbinganTa::class, 'tugas_akhir_id');
    }

<<<<<<< HEAD
    public function dokumen()
=======
    // Relasi ke Dokumen TA (proposal, draft, final)
    public function dokumen(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }

<<<<<<< HEAD
    public function revisi()
=======
    // Relasi ke Revisi TA
    public function revisi(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(RevisiTa::class, 'tugas_akhir_id');
    }

<<<<<<< HEAD
    public function sidang()
=======
    // Relasi ke Sidang TA (bisa juga hasMany jika ada banyak sidang)
    public function sidang(): HasOne
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasOne(Sidang::class, 'tugas_akhir_id');
    }

<<<<<<< HEAD
    public function notifikasi()
=======
    // Relasi ke Notifikasi TA
    public function notifikasi(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(NotifikasiTa::class, 'tugas_akhir_id');
    }

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
    public function pembimbing1()
    {
        return $this->hasOne(PeranDosenTA::class, 'tugas_akhir_id')->where('peran', 'pembimbing1');
    }

    // relasi 1 ke PeranDosenTA yang peran pembimbing2
    public function pembimbing2()
    {
        return $this->hasOne(PeranDosenTA::class, 'tugas_akhir_id')->where('peran', 'pembimbing2');
    }
}
