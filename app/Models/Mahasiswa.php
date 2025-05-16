<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa'; // nama tabel sesuai DB

    protected $fillable = ['user_id', 'nim', 'phone', 'address', 'prodi', 'angkatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class);
    }

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(NotifikasiTa::class);
    }
}
