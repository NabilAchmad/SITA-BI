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
    
<<<<<<< HEAD
    public function nilai()
=======
    public function nilai(): HasMany
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
    {
        return $this->hasMany(NilaiSidang::class);
    }

    public function beritaAcaraPasca()
    {
        return $this->hasMany(BeritaAcaraPascaSidang::class);
    }

    public function beritaAcaraPra()
    {
        return $this->hasMany(BeritaAcaraPraSidang::class);
    }
}
