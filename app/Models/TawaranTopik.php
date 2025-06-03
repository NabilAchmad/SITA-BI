<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TawaranTopik extends Model
{
    use SoftDeletes;

    protected $table = 'tawaran_topik';

    // Tambahkan 'user_id' ke fillable agar bisa diisi saat insert/update
    protected $fillable = [
        'user_id', 'dosen_id', 'judul_topik', 'deskripsi', 'kuota'
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }
}