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
        'status',
        'tanggal_pengajuan',
        'file_path', 
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
