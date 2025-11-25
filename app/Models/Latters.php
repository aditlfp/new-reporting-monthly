<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Latters extends Model
{
    /** @use HasFactory<\Database\Factories\LattersFactory> */
    use HasFactory;

    protected $fillable = [
        'cover_id',
        'latter_numbers',
        'latter_matters',
        'period',
        'report_content',
        'signature'
    ];

    public function cover(){
        return $this->belongsTo(Cover::class);
    }

    protected static function booted()
    {
        static::created(function ($upload) {
            ActivityLogs::create([
                'type' => 'upload',
                'title' => 'New letter upload from ' . auth()->user()->nama_lengkap,
                'description' => 'Added a new letter with number : ' . $upload->latter_numbers,
                'created_at' => now(),
            ]);
        });

        static::updated(function ($upload) {
            ActivityLogs::create([
                'type' => 'update',
                'title' => 'Letter update from ' . auth()->user()->nama_lengkap,
                'description' => 'Updated letter with number : ' . $upload->latter_numbers,
                'created_at' => now(),
            ]);
        });

        static::deleted(function ($upload) {
            ActivityLogs::create([
                'type' => 'delete',
                'title' => 'Data letter deleted by ' . auth()->user()->nama_lengkap,
                'description' => $upload->latter_numbers . ' this number of letter has been removed',
                'created_at' => now(),
            ]);
        });
    }
}