<?php

namespace App\Repositories\Contracts;

use App\Models\Cover;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoverRepositoryInterface
{
    public function paginateWithClient(?array $clientIds = null, int $perPage = 10): LengthAwarePaginator;
    public function paginateWithClientForDownload(?int $clientId = null, ?array $allowedClientIds = null, int $perPage = 12): LengthAwarePaginator;

    public function allClients(?array $clientIds = null): Collection;

    public function findWithClientOrFail(int $id, ?array $allowedClientIds = null): Cover;

    public function create(array $attributes): Cover;

    public function update(Cover $cover, array $attributes): Cover;

    public function delete(Cover $cover): void;
}
