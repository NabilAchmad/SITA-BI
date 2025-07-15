<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DokumenTa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'dokumen_ta';

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tugas_akhir_id',
        'tipe_dokumen',
        'status_validasi',
        'divalidasi_oleh_p1', // <-- Diperbarui dari divalidasi_oleh
        'divalidasi_oleh_p2', // <-- Kolom baru untuk Pembimbing 2
        'file_path',
        'version',
    ];

    /**
     * Relasi ke model TugasAkhir (dokumen ini milik tugas akhir mana).
     */
    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    /**
     * Relasi ke Dosen yang melakukan validasi sebagai Pembimbing 1.
     * Menggunakan foreign key 'divalidasi_oleh_p1'.
     */
    public function validatorP1(): BelongsTo
    {
        // Ganti Dosen::class dengan User::class jika Anda menggunakan model User untuk dosen.
        return $this->belongsTo(Dosen::class, 'divalidasi_oleh_p1');
    }

    /**
     * Relasi ke Dosen yang melakukan validasi sebagai Pembimbing 2.
     * Menggunakan foreign key 'divalidasi_oleh_p2'.
     */
    public function validatorP2(): BelongsTo
    {
        // Ganti Dosen::class dengan User::class jika Anda menggunakan model User untuk dosen.
        return $this->belongsTo(Dosen::class, 'divalidasi_oleh_p2');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(FileUpload::class, 'fileable');
    }
}
