<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraPraSidang extends Model
{
    protected $table = 'berita_acara_pra_sidang';

    protected $fillable = [
        'sidang_id',
        'dicetak_oleh',
        'tanggal_cetak',
        'status_dokumen',
        'file_path'
    ];

    public function sidang(): BelongsTo
    {
        return $this->belongsTo(Sidang::class);
    }

    public function pencetak(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicetak_oleh');
    }
}
