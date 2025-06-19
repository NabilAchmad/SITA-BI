<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = ['nama_ruangan', 'lokasi', 'kapasitas'];

    public function jadwalSidang()
    {
        return $this->hasMany(JadwalSidang::class);
    }
}
