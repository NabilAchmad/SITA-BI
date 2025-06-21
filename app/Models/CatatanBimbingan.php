<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanBimbingan extends Model
{
    protected $table = 'catatan_bimbingan';

    protected $fillable = [
        'bimbingan_ta_id',
        'author_type',
        'author_id',
        'catatan',
    ];

    // Relasi ke bimbingan
    public function bimbinganTa()
    {
        return $this->belongsTo(BimbinganTa::class, 'bimbingan_ta_id');
    }

    // Opsional: relasi ke mahasiswa/dosen (polimorfik manual)
    public function author()
    {
        if ($this->author_type === 'mahasiswa') {
            return $this->belongsTo(Mahasiswa::class, 'author_id');
        } else {
            return $this->belongsTo(Dosen::class, 'author_id');
        }
    }
}
