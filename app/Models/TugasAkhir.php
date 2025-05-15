<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TugasAkhir extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'file_path',
        'status',
        'tanggal_pengajuan',
        'alasan_pembatalan',  // Tambahkan kolom alasan_pembatalan
    ];



    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'uploaded_by', 'mahasiswa_id');
    }
}
