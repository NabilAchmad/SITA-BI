<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevisiTA extends Model
{
    use HasFactory;

    protected $table = 'revisi_ta';

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
