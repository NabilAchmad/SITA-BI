<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanBimbingan extends Model
{
    use HasFactory;

    protected $table = 'catatan_bimbingan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // ✅ PERBAIKAN: Menambahkan semua kolom yang dibutuhkan oleh service
    protected $fillable = [
        'bimbingan_ta_id',
        'catatan',
        'author_type',
        'author_id',
    ];

    /**
     * Get the parent bimbingan model.
     */
    public function bimbinganTa()
    {
        return $this->belongsTo(BimbinganTA::class);
    }

    /**
     * Get the owning author model (can be Mahasiswa or Dosen).
     */
    public function author()
    {
        return $this->morphTo();
    }
}
