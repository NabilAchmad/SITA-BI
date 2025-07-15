<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FileUpload extends Model
{
    use HasFactory;

    protected $table = 'file_uploads';

    protected $fillable = [
        'file_path',
        'original_name',
        'file_type',
        'fileable_id',
        'fileable_type',
    ];

    /**
     * Mendefinisikan relasi polimorfik "fileable".
     * Ini memungkinkan model FileUpload terhubung ke model lain
     * seperti PendaftaranSidang atau DokumenTa.
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
