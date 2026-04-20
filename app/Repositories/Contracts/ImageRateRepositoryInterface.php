<?php

namespace App\Repositories\Contracts;

use App\Models\ImageRate;
use App\Models\UploadImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ImageRateRepositoryInterface
{
    public function paginateWithSummary(array $filters, int $perPage = 15): array;

    public function findUploadByAreaName(string $normalizedImageName): ?UploadImage;

    public function create(array $attributes): ImageRate;

    public function findOrFail(int $id): ImageRate;

    public function update(ImageRate $rate, array $attributes): ImageRate;

    public function delete(ImageRate $rate): void;
}
