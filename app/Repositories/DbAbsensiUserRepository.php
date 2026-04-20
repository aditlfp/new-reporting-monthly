<?php

namespace App\Repositories;

use App\Repositories\Contracts\AbsensiUserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DbAbsensiUserRepository implements AbsensiUserRepositoryInterface
{
    public function getUsersWithPosition(): Collection
    {
        return DB::connection('dbAbsensi')
            ->table('users')
            ->join('divisis', 'users.devisi_id', '=', 'divisis.id')
            ->join('jabatans', 'divisis.jabatan_id', '=', 'jabatans.id')
            ->select(
                'users.id',
                'users.nama_lengkap',
                'users.email',
                'users.image',
                'divisis.name',
                'jabatans.name_jabatan'
            )
            ->get();
    }
}
