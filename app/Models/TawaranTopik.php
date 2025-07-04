<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TawaranTopik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tawaran_topik';
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'kuota' => 'integer', // Memastikan kuota selalu bertipe integer
    ];

    /**
     * Relasi ke User yang membuat tawaran topik.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Shortcut untuk mendapatkan data Dosen melalui User.
     */
    public function dosen()
    {
        return $this->hasOneThrough(Dosen::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function historyTopik(): HasMany
    {
        return $this->hasMany(HistoryTopikMahasiswa::class);
    }

    public function tugasAkhir(): HasMany
    {
        return $this->hasMany(TugasAkhir::class, 'tawaran_topik_id');
    }

    /**
     * Scope untuk mengambil topik yang masih tersedia.
     */
    public function scopeAvailable($query)
    {
        return $query->where('kuota', '>', 0);
    }

    /**
     * Method helper untuk memeriksa apakah sebuah topik tersedia.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->kuota > 0;
    }
}
