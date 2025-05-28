<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudulTA extends Model
{
    protected $table = 'tugas_akhir';
    protected $fillable = ['judul', 'mahasiswa_id', 'status', 'dosen_pembimbing', 'tanggal_acc'];
}
