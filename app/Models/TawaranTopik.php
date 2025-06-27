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
        return $this->belongsTo(Dosen::class);
    }

    public function historyTopik()
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }
}
