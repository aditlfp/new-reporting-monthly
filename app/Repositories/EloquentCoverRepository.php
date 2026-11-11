<?php

namespace App\Repositories;

use App\Models\Clients;
use App\Models\Cover;
use App\Repositories\Contracts\CoverRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentCoverRepository implements CoverRepositoryInterface
{
    public function paginateWithClient(?array $clientIds = null, int $perPage = 10): LengthAwarePaginator
    {
        return Cover::query()
            ->with('client')
            ->when($clientIds !== null, fn ($query) => $query->whereIn('clients_id', $clientIds))
            ->latest()
            ->paginate($perPage);
    }

    public function paginateWithClientForDownload(?int $clientId = null, ?array $allowedClientIds = null, int $perPage = 12): LengthAwarePaginator
    {
        return Cover::query()
            ->with('client')
            ->when($allowedClientIds !== null, fn ($query) => $query->whereIn('clients_id', $allowedClientIds))
            ->when($clientId, fn ($query) => $query->where('clients_id', $clientId))
            ->latest()
            ->paginate($perPage);
    }

    public function allClients(?array $clientIds = null): Collection
    {
        return Clients::query()
            ->when($clientIds !== null, fn ($query) => $query->whereIn('id', $clientIds))
            ->get();
    }

    public function findWithClientOrFail(int $id, ?array $allowedClientIds = null): Cover
    {
        return Cover::query()
            ->with('client')
            ->when($allowedClientIds !== null, fn ($query) => $query->whereIn('clients_id', $allowedClientIds))
            ->findOrFail($id);
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
