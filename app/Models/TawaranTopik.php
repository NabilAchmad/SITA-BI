<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TawaranTopik extends Model
{
    use SoftDeletes;

    protected $table = 'tawaran_topik';

    protected $fillable = [
        'user_id','dosen_id', 'judul_topik', 'deskripsi', 'kuota'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function historyTopik()
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }

    public function tugasAkhir()
    {
        return $this->hasMany(TugasAkhir::class, 'tawaran_topik_id');
    }

    // ✅ Tambahkan ini
    public function scopeAvailable($query)
    {
        return $query->where('kuota', '>', 0);
    }

    // ✅ Fungsi bantu lain jika ingin dipakai di controller
    public function isAvailable()
    {
        return $this->kuota > 0;
    }
}
