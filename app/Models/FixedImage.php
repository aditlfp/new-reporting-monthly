<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedImage extends Model
{
    protected $fillable = ['upload_image_id', 'user_id','clients_id'];

    public function image()
    {
        return $this->belongsTo(UploadImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->belongsTo(Clients::class);
    }
}
