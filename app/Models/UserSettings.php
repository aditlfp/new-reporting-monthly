<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'data_theme'
    ];

    protected $casts = [
        'data_theme' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
