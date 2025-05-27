<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JabatanDosen extends Model
{
    protected $table = 'jabatan_dosen';

    protected $fillable = ['user_id', 'jabatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
