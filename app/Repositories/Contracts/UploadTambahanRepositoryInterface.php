<?php

namespace App\Repositories\Contracts;

use App\Models\UploadTambahan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UploadTambahanRepositoryInterface
{
    public function createWithItems(array $header, array $items): UploadTambahan;

    public function paginateByUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function paginateAdmin(array $filters, array $userIds, int $perPage = 20): LengthAwarePaginator;

    public function countUploadsByUsers(array $userIds, int $month, int $year): Collection;

    public function getByUserAndPeriod(int $userId, int $month, int $year): Collection;

    public function findWithItems(int $id): ?UploadTambahan;
}

