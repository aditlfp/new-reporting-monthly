<?php

namespace App\Repositories;

use App\Models\Jabatan;
use App\Models\User;
use App\Repositories\Contracts\RoleScopeRepositoryInterface;

class EloquentRoleScopeRepository implements RoleScopeRepositoryInterface
{
    public function getJabatanIdsByTypes(array $types): array
    {
        if (empty($types)) {
            return [];
        }

        return Jabatan::query()
            ->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(type_jabatan)'), $types)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function getUserIdsByJabatanAndClient(array $jabatanIds, ?int $clientId = null): array
    {
        if (empty($jabatanIds)) {
            return [];
        }

        return User::query()
            ->whereIn('jabatan_id', $jabatanIds)
            ->when($clientId, function ($query, $value) {
                $query->whereHas('kerjasama.client', function ($q) use ($value) {
                    $q->where('id', $value);
                });
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
