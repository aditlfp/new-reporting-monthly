<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadTambahan extends Model
{
    protected $fillable = [
        'user_id',
        'clients_id',
    ];

    public function items()
    {
        return $this->hasMany(UploadTambahanItem::class, 'upload_tambahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Clients::class, 'clients_id');
    }
}

