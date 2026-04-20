<?php

namespace App\Repositories\Contracts;

use App\Models\Latters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface LattersRepositoryInterface
{
    public function paginateWithCoverClient(int $perPage = 10): LengthAwarePaginator;

    public function getAllCovers(): Collection;

    public function findWithCoverClientOrFail(int $id): Latters;

    public function create(array $attributes): Latters;

    public function update(Latters $latters, array $attributes): Latters;

    public function deleteById(int $id): bool;
}
