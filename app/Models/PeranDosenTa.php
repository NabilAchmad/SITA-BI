<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeranDosenTa extends Model
{
    protected $table = 'peran_dosen_ta';

    protected $fillable = [
        'dosen_id', 'tugas_akhir_id', 'peran'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }
}
