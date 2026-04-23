<?php

namespace App\Repositories\Contracts;

use App\Models\qrCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface QrCodeRepositoryInterface
{
    public function paginate(string $search = '', int $perPage = 20): LengthAwarePaginator;

    public function allDataValues(): Collection;

    public function findOrFail(int $id): qrCode;

    public function create(array $attributes): qrCode;

    public function update(qrCode $model, array $attributes): qrCode;

    public function delete(qrCode $model): void;
}
