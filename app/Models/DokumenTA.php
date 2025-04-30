<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenTA extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen_ta';

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
