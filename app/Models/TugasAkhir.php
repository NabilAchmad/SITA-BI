<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasAkhir extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_akhir';  // sesuaikan dengan nama tabel di DB

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'status',
        'tanggal_pengajuan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }


    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

    public function bimbingan(): HasMany
    {
        return $this->hasMany(BimbinganTa::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenTa::class);
    }

    public function revisi(): HasMany
    {
        return $this->hasMany(RevisiTa::class);
    }

    public function sidang(): HasMany
    {
        return $this->hasMany(Sidang::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(NotifikasiTa::class);
    }
}
