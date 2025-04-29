<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
