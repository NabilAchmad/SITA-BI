<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevisiTA extends Model
{
    use HasFactory;

    protected $table = 'revisi_ta';

    // Tambahkan fillable agar mass assignment diizinkan
    protected $fillable = [
        'catatan',
        'status_revisi',
        'tugas_akhir_id', // tambahkan ini agar mass assignment diizinkan
        // tambahkan field lain jika ada, misal: 'dosen_id', dst.
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}