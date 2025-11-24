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
        return $this->belongsTo(Clients::class);
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

}
