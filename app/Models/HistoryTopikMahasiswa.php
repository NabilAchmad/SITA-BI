<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryTopikMahasiswa extends Model
{
    protected $table = 'history_topik_mahasiswa';

    protected $fillable = [
        'mahasiswa_id', 'tawaran_topik_id', 'status_topik', 'tanggal_pemilihan'
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function tawaranTopik(): BelongsTo
    {
        return $this->belongsTo(TawaranTopik::class);
    }
}
