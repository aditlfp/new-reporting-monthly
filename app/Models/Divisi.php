<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $connection = 'dbAbsensi';
    protected $table = 'divisis';

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
