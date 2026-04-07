<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class qrCode extends Model
{
    protected $fillable = [
        'qr',
        'data'
    ];
}
