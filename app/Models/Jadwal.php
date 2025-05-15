<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal_sidang';
    protected $fillable = ['tanggal', 'kegiatan', 'lokasi'];
}
