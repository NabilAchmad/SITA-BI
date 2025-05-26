<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa'; // nama tabel sesuai DB

    protected $fillable = ['user_id', 'nim', 'phone', 'address', 'prodi', 'angkatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class); // ✔️ Ini yang benar jika hanya 1 TA per mahasiswa
    }

    // Jika ingin akses langsung peran dosen lewat mahasiswa
    public function peranDosenTA()
    {
        return $this->hasManyThrough(
            PeranDosenTA::class,
            TugasAkhir::class,
            'mahasiswa_id',
            'tugas_akhir_id',
            'id',
            'id'
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

    public function pembimbing1()
    {
        return $this->tugasAkhir()->with('pembimbing1Dosen')->first()->pembimbing1Dosen ?? null;
    }

    public function pembimbing2()
    {
        return $this->tugasAkhir()->with('pembimbing2Dosen')->first()->pembimbing2Dosen ?? null;
    }
}
