<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\JadwalSidang;

class Sidang extends Model
{
    use SoftDeletes;

    protected $table = 'sidang';

    protected $fillable = ['tugas_akhir_id', 'jenis_sidang', 'status'];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function jadwalSidang()
    {
        return $this->hasOne(JadwalSidang::class);
    }
    
    public function nilai(): HasMany
    {
        return $this->hasMany(NilaiSidang::class);
    }

    public function beritaAcaraPasca(): HasMany
    {
        return $this->hasMany(BeritaAcaraPascaSidang::class);
    }

    public function beritaAcaraPra(): HasMany
    {
        return $this->hasMany(BeritaAcaraPraSidang::class);
    }
}
