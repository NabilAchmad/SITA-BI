<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewDokumenTA extends Model
{
    use HasFactory;

    protected $table = 'review_dokumen_ta';

    public function dokumen()
    {
        return $this->belongsTo(DokumenTA::class, 'dokumen_ta_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Dosen::class, 'reviewer_id');
    }
}
