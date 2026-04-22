<?php

namespace App\Repositories\Contracts;

use App\Models\Cover;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoverRepositoryInterface
{
    public function paginateWithClient(int $perPage = 10): LengthAwarePaginator;
    public function paginateWithClientForDownload(?int $clientId = null, int $perPage = 12): LengthAwarePaginator;

    public function allClients(): Collection;

    public function findWithClientOrFail(int $id): Cover;

    public function create(array $attributes): Cover;

    public function update(Cover $cover, array $attributes): Cover;

    public function delete(Cover $cover): void;
}
