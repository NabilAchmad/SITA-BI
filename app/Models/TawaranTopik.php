<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TawaranTopik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tawaran_topik';

    protected $fillable = [
        'user_id',
        'judul_topik',
        'deskripsi',
        'kuota',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

<<<<<<< HEAD
=======
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relasi ke user (dosen) yang menawarkan topik
     */
>>>>>>> 818b63797bbbddf7128e4a27dc033151ed6e5eb5
    public function dosen()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

<<<<<<< HEAD
    public function historyTopik()
=======
    /**
     * Relasi ke tugas akhir yang menggunakan topik ini
     */
    public function tugasAkhir()
>>>>>>> 818b63797bbbddf7128e4a27dc033151ed6e5eb5
    {
        return $this->hasMany(TugasAkhir::class);
    }

    /**
     * Cek ketersediaan kuota topik
     */
    public function isAvailable()
    {
        return $this->tugasAkhir()->count() < $this->kuota;
    }

    /**
     * Scope untuk topik yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM tugas_akhir WHERE tugas_akhir.tawaran_topik_id = tawaran_topik.id) < tawaran_topik.kuota');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
