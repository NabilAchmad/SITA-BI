<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CatatanBimbingan extends Model
{
    use HasFactory;

    protected $table = 'catatan_bimbingan';

    protected $guarded = ['id'];

    /**
     * Relasi ke sesi bimbingan (induknya).
     */
    public function bimbinganTa(): BelongsTo
    {
        return $this->belongsTo(BimbinganTA::class, 'bimbingan_ta_id');
    }

    /**
     * ✅ [PERBAIKAN FINAL DAN KUNCI]
     * Definisikan relasi polimorfik 'author' dengan benar menggunakan morphTo().
     * Ini akan secara otomatis menemukan Dosen atau Mahasiswa berdasarkan
     * isi kolom 'author_type' dan 'author_id'.
     */
    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}
