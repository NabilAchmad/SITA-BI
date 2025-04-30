<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $fillable = ['user_id', 'nim', 'prodi', 'angkatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tugasAkhir(): HasMany
    {
        return $this->hasMany(TugasAkhir::class);
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
