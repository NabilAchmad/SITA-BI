<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryPerubahanJadwal extends Model
{
    use HasFactory;

    protected $table = 'history_perubahan_jadwal';

    protected $fillable = [
        'bimbingan_ta_id',
        'mahasiswa_id',
        'tanggal_lama',
        'jam_lama',
        'tanggal_baru',
        'jam_baru',
        'alasan_perubahan',
        'status',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(BimbinganTa::class, 'bimbingan_ta_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
