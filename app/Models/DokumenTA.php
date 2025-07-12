<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Ini penting agar metode create() bisa berfungsi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tugas_akhir_id',
        'tipe_dokumen',
        'file_path',
        'version',
        'status_validasi',
        'divalidasi_oleh',
        'catatan_reviewer',
        // 'bimbingan_ta_id' // <-- Jika Anda mengikuti rekomendasi saya sebelumnya untuk menambah kolom ini
    ];

    /**
     * Relasi ke model TugasAkhir (dokumen ini milik tugas akhir mana).
     */
    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    /**
     * Relasi ke user yang melakukan validasi.
     * Kolom 'divalidasi_oleh' akan terhubung ke primary key di tabel users.
     */
    public function divalidasiOleh(): BelongsTo
    {
        // Relasi ini terhubung ke kolom 'divalidasi_oleh' pada tabel ini.
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    /**
     * Relasi ke user yang melakukan scan.
     * Kolom 'scanned_by' akan terhubung ke primary key di tabel users.
     */
    public function scannedBy(): BelongsTo
    {
        // Relasi ini terhubung ke kolom 'scanned_by' pada tabel ini.
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
