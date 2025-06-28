<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TugasAkhir extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'status',
        'alasan_pembatalan',
        'tanggal_pengajuan',
        'file_path',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function peranDosenTa()
    {
        return $this->hasMany(PeranDosenTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Bimbingan TA
    public function bimbingan()
    {
        return $this->hasMany(BimbinganTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Dokumen TA (proposal, draft, final)
    public function dokumen()
    {
        return $this->hasMany(DokumenTa::class, 'tugas_akhir_id');
    }

    // Relasi ke Revisi TA
    public function revisi()
    {
        return $this->hasMany(RevisiTa::class, 'tugas_akhir_id');
    }

    // public function sidang()
    // Relasi ke Sidang TA (bisa juga hasMany jika ada banyak sidang)
    public function sidang()
    {
        return $this->hasMany(Sidang::class);
        // return $this->hasOne(Sidang::class, 'tugas_akhir_id');
    }
    // Relasi ke Notifikasi TA
    public function notifikasi()
    {
        return $this->hasMany(NotifikasiTa::class, 'tugas_akhir_id');
    }

    public function dosenPembimbing()
    {
        return $this->hasMany(PeranDosenTA::class);
    }

    // relasi banyak ke PeranDosenTA
    public function peranDosen()
    {
        return $this->hasMany(PeranDosenTA::class, 'tugas_akhir_id');
    }

    public function sidangTerakhir()
    {
        return $this->hasOne(Sidang::class)
            ->whereIn('jenis_sidang', ['akhir', 'proposal'])
            ->latestOfMany();
    }
}
