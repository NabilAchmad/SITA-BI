<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalSidang extends Model
{

    protected $table = 'jadwal_sidang';

    protected $fillable = [
        'sidang_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jenis_sidang',
        'ruangan_id'
    ];

    public function sidang()
    {
        return $this->belongsTo(Sidang::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
