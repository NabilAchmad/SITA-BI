<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    use HasFactory;

    /**
     * Menonaktifkan timestamps default (created_at, updated_at) 
     * karena kita hanya butuh 'created_at' yang di-handle secara manual.
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    /**
     * Pastikan tidak ada relasi ke User model di sini.
     * Hapus fungsi seperti public function user() jika ada.
     */
}