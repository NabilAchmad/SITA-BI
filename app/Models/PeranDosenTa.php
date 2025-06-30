<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeranDosenTa extends Model
{
    use HasFactory;

    protected $table = 'peran_dosen_ta';
    protected $guarded = ['id'];

    // DEFINISIKAN SEMUA PERAN SEBAGAI KONSTANTA
    // Ini menghilangkan 'magic strings' dan menjadi sumber kebenaran tunggal.
    const PERAN_PEMBIMBING_1 = 'pembimbing1';
    const PERAN_PEMBIMBING_2 = 'pembimbing2';
    const PERAN_PENGUJI_1 = 'penguji1';
    const PERAN_PENGUJI_2 = 'penguji2';
    const PERAN_PENGUJI_3 = 'penguji3';
    const PERAN_PENGUJI_4 = 'penguji4';


    // Relasi ke model induk
    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
