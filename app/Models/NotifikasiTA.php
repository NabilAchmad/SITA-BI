<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiTA extends Model {
    use HasFactory;

    protected $table = 'notifikasi_ta';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tugasAkhir() {
        return $this->belongsTo(TugasAkhir::class);
    }
}
