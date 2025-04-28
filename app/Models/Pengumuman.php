<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes; // Aktifkan fitur soft delete

    protected $fillable = [
        'judul',
        'isi',
        'audiens',
        'user_id',
        'tanggal_dibuat'
    ];

    // Relasi ke user (pembuat pengumuman)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
