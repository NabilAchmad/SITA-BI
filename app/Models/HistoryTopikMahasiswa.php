<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTopikMahasiswa extends Model
{
    use HasFactory;

    // Gunakan nama tabel singular, Laravel akan menanganinya
    protected $table = 'history_topik_mahasiswa';

    protected $guarded = ['id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function tawaranTopik()
    {
        return $this->belongsTo(TawaranTopik::class, 'tawaran_topik_id');
    }
}
