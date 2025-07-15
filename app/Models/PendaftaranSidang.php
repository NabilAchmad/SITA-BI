<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- Tambahkan ini

class PendaftaranSidang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pendaftaran_sidang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tugas_akhir_id',
        'status_verifikasi',
        'status_pembimbing_1',
        'status_pembimbing_2',
        'catatan_admin',
        'catatan_pembimbing_1',
        'catatan_pembimbing_2',
        'file_naskah_ta',
        'file_rapor',
        'file_toeic',
        'file_ijazah_slta',
        'file_bebas_jurusan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'catatan_admin' => 'string',
        'catatan_pembimbing_1' => 'string',
        'catatan_pembimbing_2' => 'string',
    ];

    /**
     * Mendapatkan data Tugas Akhir yang terkait dengan pendaftaran sidang ini.
     */
    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function sidang(): HasOne
    {
        return $this->hasOne(Sidang::class, 'pendaftaran_sidang_id');
    }
}
