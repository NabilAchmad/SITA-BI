<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    protected $table = 'sidang';
    protected $fillable = ['judul', 'tanggal', 'nilai', 'status'];
}
