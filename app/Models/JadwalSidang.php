<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalSidang extends Model
{
    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'jadwal_sidang';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'sidang_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan_id',
        // 'jenis_sidang' dihapus karena tidak ada di skema tabel yang Anda berikan.
        // Menambahkannya di sini bisa menyebabkan error SQL jika kolomnya tidak ada.
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Sidang.
     * Setiap jadwal sidang pasti milik satu sidang.
     */
    public function sidang(): BelongsTo
    {
        return $this->belongsTo(Sidang::class, 'sidang_id');
    }

    /**
     * [PERBAIKAN] Mendefinisikan relasi "belongsTo" ke model Ruangan.
     * Setiap jadwal sidang pasti memiliki satu ruangan.
     * Menambahkan foreign key 'ruangan_id' secara eksplisit membuatnya lebih aman.
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}
