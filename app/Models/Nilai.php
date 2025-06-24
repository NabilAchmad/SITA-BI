<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai_sidang';
    protected $fillable = ['mahasiswa_id', 'sidang_id', 'nilai_angka', 'nilai_huruf'];
    public function sidang() {
        return $this->belongsTo(Sidang::class);
    }
}
