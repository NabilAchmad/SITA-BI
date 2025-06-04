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

    protected $casts = ['audience' => 'array'];
=======
    protected $casts = ['audience' => 'array',];
>>>>>>> 818b63797bbbddf7128e4a27dc033151ed6e5eb5

    protected $dates = ['deleted_at']; // penting untuk soft delete

    protected $fillable = [
        'judul',
        'isi',
        'dibuat_oleh',
        'audiens',
        'tanggal_dibuat'
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
