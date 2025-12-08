<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingSync extends Model
{
    protected $fillable = ['user_id', 'type', 'payload', 'status'];

    protected $casts = [
        'payload' => 'array'
    ];
}

