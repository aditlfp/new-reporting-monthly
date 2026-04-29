<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadImageRating extends Model
{
    protected $fillable = [
        'upload_image_id',
        'rating_value',
        'rating_reason',
        'rated_by_user_id',
        'rated_at',
    ];

    public function uploadImage()
    {
        return $this->belongsTo(UploadImage::class);
    }

    public function ratedBy()
    {
        return $this->belongsTo(User::class, 'rated_by_user_id');
    }
}

