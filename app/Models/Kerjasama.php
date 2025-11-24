<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kerjasama extends Model
{
    protected $connection = 'dbAbsensi';
    protected $table = 'kerjasamas';

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
