<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengumuman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengumuman';
<<<<<<< HEAD
    protected $casts = ['audience' => 'array',];

    protected $dates = ['deleted_at']; // penting untuk soft delete
=======
<<<<<<< HEAD
=======
    protected $casts = ['audience' => 'array',];

    protected $dates = ['deleted_at']; // penting untuk soft delete
>>>>>>> 905ddd514a1f02e7724f6a26e444a4bf3ee356ee
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a

    protected $fillable = [
        'judul',
        'isi',
        'dibuat_oleh',
        'audiens',
        'tanggal_dibuat'
    ];

<<<<<<< HEAD
=======

>>>>>>> 905ddd514a1f02e7724f6a26e444a4bf3ee356ee
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
