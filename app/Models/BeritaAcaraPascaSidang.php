<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraPascaSidang extends Model
{
    protected $table = 'berita_acara_pasca_sidang';

    protected $fillable = ['sidang_id', 'kesimpulan', 'hasil_sidang', 'catatan'];

    public function sidang(): BelongsTo
    {
        return $this->belongsTo(Sidang::class);
    }
}
