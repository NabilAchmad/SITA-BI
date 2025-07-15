<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Pastikan ini di-import
use App\Models\JadwalSidang;

class Sidang extends Model
{
    use SoftDeletes;

    protected $table = 'sidang';

    protected $fillable = ['tugas_akhir_id', 'status'];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function jadwalSidang()
    {
        return $this->hasMany(JadwalSidang::class);
    }

    public function nilaiSidang(): HasMany
    {
        return $this->hasMany(NilaiSidang::class);
    }

    public function jadwal() {
        return $this->hasOne(JadwalSidang::class, 'sidang_id');
    }

    public function beritaAcaraPasca()
    {
        return $this->hasMany(BeritaAcaraPascaSidang::class);
    }

    public function beritaAcaraPra()
    {
        return $this->hasMany(BeritaAcaraPraSidang::class);
    }

    protected function statusHasilFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $status = $this->status_hasil;
                $badgeClass = 'bg-dark'; // Default badge
                $text = 'Tidak Diketahui';

                switch ($status) {
                    case 'menunggu_penjadwalan':
                        $badgeClass = 'bg-warning text-dark';
                        $text = 'Menunggu Jadwal';
                        break;
                    case 'dijadwalkan':
                        $badgeClass = 'bg-info';
                        $text = 'Dijadwalkan';
                        break;
                    case 'lulus':
                        $badgeClass = 'bg-success';
                        $text = 'Lulus';
                        break;
                    case 'lulus_revisi':
                        $badgeClass = 'bg-primary';
                        $text = 'Lulus dengan Revisi';
                        break;
                    case 'tidak_lulus':
                        $badgeClass = 'bg-danger';
                        $text = 'Tidak Lulus';
                        break;
                }

                // Mengembalikan string HTML lengkap untuk badge
                return "<span class=\"badge {$badgeClass}\">{$text}</span>";
            }
        );
    }
}
