<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSidang extends Model
{
    protected $table = 'nilai_sidang';

    protected $fillable = [
        'sidang_id', 'dosen_id', 'aspek', 'komentar', 'skor'
    ];

    public function sidang(): BelongsTo
    {
        return $this->belongsTo(Sidang::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
