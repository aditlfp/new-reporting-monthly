<?php

namespace App\Repositories;

use App\Models\qrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentQrCodeRepository implements QrCodeRepositoryInterface
{
    public function paginate(string $search = '', int $perPage = 20): LengthAwarePaginator
    {
        return qrCode::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('data', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function allDataValues(): Collection
    {
        return qrCode::query()
            ->whereNotNull('data')
            ->pluck('data');
    }

    public function findOrFail(int $id): qrCode
    {
        return qrCode::query()->findOrFail($id);
    }

    public function create(array $attributes): qrCode
    {
        return qrCode::query()->create($attributes);
    }

    public function update(qrCode $model, array $attributes): qrCode
    {
        $model->update($attributes);

        return $model->fresh();
    }

    public function delete(qrCode $model): void
    {
        $model->delete();
    }
}
