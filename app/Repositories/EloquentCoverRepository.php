<?php

namespace App\Repositories;

use App\Models\Clients;
use App\Models\Cover;
use App\Repositories\Contracts\CoverRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentCoverRepository implements CoverRepositoryInterface
{
    public function paginateWithClient(int $perPage = 10): LengthAwarePaginator
    {
        return Cover::query()->with('client')->latest()->paginate($perPage);
    }

    public function allClients(): Collection
    {
        return Clients::all();
    }

    public function findWithClientOrFail(int $id): Cover
    {
        return Cover::query()->with('client')->findOrFail($id);
    }

    public function create(array $attributes): Cover
    {
        return Cover::query()->create($attributes);
    }

    public function update(Cover $cover, array $attributes): Cover
    {
        $cover->update($attributes);

        return $cover->fresh(['client']);
    }

    public function delete(Cover $cover): void
    {
        $cover->delete();
    }
}
