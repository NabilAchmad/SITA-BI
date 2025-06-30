<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TawaranTopik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tawaran_topik';
    protected $guarded = ['id'];

    /**
     * PERBAIKAN: Mengganti relasi ke Dosen menjadi ke User, sesuai permintaan.
     * Laravel akan mencari kolom 'user_id' di tabel 'tawaran_topik'.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ini adalah 'shortcut' untuk mendapatkan data Dosen melalui User.
     */
    public function dosen()
    {
        // Asumsi: Setiap User yang membuat topik adalah Dosen.
        // Relasi ini mencari Dosen yang memiliki user_id yang sama dengan user_id topik ini.
        return $this->hasOneThrough(Dosen::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }

    public function tugasAkhir(): HasMany
    {
        return $this->hasMany(TugasAkhir::class, 'tawaran_topik_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('kuota', '>', 0)->where('status', 'tersedia');
    }

    public function isAvailable(): bool
    {
        return $this->kuota > 0 && $this->status === 'tersedia';
    }
}
