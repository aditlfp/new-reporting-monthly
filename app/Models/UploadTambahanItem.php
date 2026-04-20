<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadTambahanItem extends Model
{
    protected $fillable = [
        'upload_tambahan_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'keterangan',
    ];

    public function uploadTambahan()
    {
        return $this->belongsTo(UploadTambahan::class, 'upload_tambahan_id');
    }
}

