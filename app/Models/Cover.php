<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{
    /** @use HasFactory<\Database\Factories\CoverFactory> */
    use HasFactory;

    protected $fillable = [
        'clients_id',
        'jenis_rekap',
        'img_src_1',
        'img_src_2',
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class, 'clients_id', 'id');
    }

    public function getClientNameFormattedAttribute()
    {
        return ucwords(strtolower($this->client->name));
    }

    public function getJenisRekapFormattedAttribute()
    {
        return ucwords(strtolower($this->jenis_rekap));
    }

    protected static function booted()
    {
        static::created(function ($cover) {
            ActivityLogs::create([
                'type' => 'upload',
                'title' => 'New cover upload from ' . (auth()->check() ? auth()->user()->nama_lengkap : 'System'),
                'description' => $cover->client?->name . ' added a new cover',
            ]);
        });

        static::updated(function ($cover) {
            ActivityLogs::create([
                'type' => 'update',
                'title' => 'Cover updated by ' . (auth()->check() ? auth()->user()->nama_lengkap : 'System'),
                'description' => $cover->client?->name . ' cover has been updated',
            ]);
        });

        static::deleted(function ($cover) {
            ActivityLogs::create([
                'type' => 'delete',
                'title' => 'Cover deleted by ' . (auth()->check() ? auth()->user()->nama_lengkap : 'System'),
                'description' => $cover->client?->name . ' cover has been removed',
            ]);
        });
    }

}
