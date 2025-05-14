<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudulTA extends Model
{
    protected $table = 'tugas_akhirs';
    protected $fillable = ['judul', 'mahasiswa_id', 'status', 'dosen_pembimbing'];
}
