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

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(NotifikasiTa::class);
    }

    public function pembimbing1()
    {
        return $this->tugasAkhir()->with('pembimbing1')->first()->pembimbing1 ?? null;
    }

    public function pembimbing2()
    {
        return $this->tugasAkhir()->with('pembimbing2')->first()->pembimbing2 ?? null;
    }
}
