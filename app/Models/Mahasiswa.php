<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa'; // nama tabel sesuai DB

    protected $fillable = ['user_id', 'nim', 'prodi', 'angkatan', 'kelas'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id'); // ✔️ Ini yang benar jika hanya 1 TA per mahasiswa
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

    public function historyTopik()
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
