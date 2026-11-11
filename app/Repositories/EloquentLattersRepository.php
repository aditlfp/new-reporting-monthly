<?php

namespace App\Repositories;

use App\Models\Cover;
use App\Models\Latters;
use App\Repositories\Contracts\LattersRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentLattersRepository implements LattersRepositoryInterface
{
    public function paginateWithCoverClient(?array $allowedClientIds = null, int $perPage = 10): LengthAwarePaginator
    {
        return Latters::query()
            ->with(['cover.client'])
            ->when($allowedClientIds !== null, fn ($query) => $query->whereHas('cover', fn ($cover) => $cover->whereIn('clients_id', $allowedClientIds)))
            ->latest()
            ->paginate($perPage);
    }

    public function getAllCovers(?array $allowedClientIds = null): Collection
    {
        return Cover::query()
            ->with('client')
            ->when($allowedClientIds !== null, fn ($query) => $query->whereIn('clients_id', $allowedClientIds))
            ->get();
    }

    public function findWithCoverClientOrFail(int $id, ?array $allowedClientIds = null): Latters
    {
        return Latters::query()
            ->with(['cover.client'])
            ->when($allowedClientIds !== null, fn ($query) => $query->whereHas('cover', fn ($cover) => $cover->whereIn('clients_id', $allowedClientIds)))
            ->findOrFail($id);
    }

    public function create(array $attributes): Latters
    {
        return Latters::query()->create($attributes);
    }

    public function update(Latters $latters, array $attributes): Latters
    {
        $latters->update($attributes);

        return $latters->fresh(['cover.client']);
    }

    public function deleteById(int $id): bool
    {
        return (bool) Latters::query()->whereKey($id)->delete();
    }
}
