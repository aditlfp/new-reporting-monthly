<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UploadImage extends Model
{
    protected $fillable = [
        "user_id",
        "clients_id",
        "img_before",
        "img_proccess",
        "img_final",
        "note",
        "max_data",
        "status",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->belongsTo(Clients::class, 'clients_id', 'id');
    }

    public function scopeSearchFilters($query, $filters)
    {
        return $query
            ->when($filters['month'] ?? null, function ($q, $month) {
                $q->whereMonth('created_at', $month);
            })
            ->when($filters['client_id'] ?? null, function ($q, $client_id) {
                $q->where('clients_id', $client_id);
            });
    }

    protected static function booted()
    {
        static::created(function ($upload) {
            ActivityLogs::create([
                'type' => 'upload',
                'title' => 'New Image Laporan added from ' . $upload->user->nama_lengkap,
                'description' => $upload->clients->name . ' added a new file',
                'created_at' => now(),
            ]);
        });

        static::deleted(function ($upload) {
            ActivityLogs::create([
                'type' => 'delete',
                'title' => 'Image Laporan deleted by ' . auth()->user()->nama_lengkap,
                'description' => $upload->clients->name . ' file has been removed',
                'created_at' => now(),
            ]);
        });

    }
}
