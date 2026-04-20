<?php

namespace App\Repositories;

use App\Models\Cover;
use App\Models\Latters;
use App\Repositories\Contracts\LattersRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentLattersRepository implements LattersRepositoryInterface
{
    public function paginateWithCoverClient(int $perPage = 10): LengthAwarePaginator
    {
        return Latters::query()->with(['cover.client'])->latest()->paginate($perPage);
    }

    public function getAllCovers(): Collection
    {
        return Cover::query()->get();
    }

    public function findWithCoverClientOrFail(int $id): Latters
    {
        return Latters::query()->with(['cover.client'])->findOrFail($id);
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
