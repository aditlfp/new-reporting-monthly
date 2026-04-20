<?php

namespace App\Repositories;

use App\Models\UploadTambahan;
use App\Repositories\Contracts\UploadTambahanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentUploadTambahanRepository implements UploadTambahanRepositoryInterface
{
    public function createWithItems(array $header, array $items): UploadTambahan
    {
        return DB::transaction(function () use ($header, $items) {
            $uploadTambahan = UploadTambahan::query()->create($header);
            $uploadTambahan->items()->createMany($items);

            return $uploadTambahan->load('items');
        });
    }

    public function paginateByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return UploadTambahan::query()
            ->withCount('items')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function paginateAdmin(array $filters, array $userIds, int $perPage = 20): LengthAwarePaginator
    {
        return UploadTambahan::query()
            ->withCount('items')
            ->whereIn('user_id', $userIds)
            ->when(!empty($filters['mitra']), function ($query) use ($filters) {
                $query->where('clients_id', (int) $filters['mitra']);
            })
            ->when(!empty($filters['month']), function ($query) use ($filters) {
                $query->whereMonth('created_at', (int) $filters['month']);
            })
            ->when(!empty($filters['year']), function ($query) use ($filters) {
                $query->whereYear('created_at', (int) $filters['year']);
            })
            ->latest()
            ->paginate($perPage);
    }

    public function countUploadsByUsers(array $userIds, int $month, int $year): Collection
    {
        if (empty($userIds)) {
            return collect();
        }

        return UploadTambahan::query()
            ->select('user_id', DB::raw('COUNT(*) as total_uploads'))
            ->whereIn('user_id', $userIds)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('user_id')
            ->get();
    }

    public function getByUserAndPeriod(int $userId, int $month, int $year): Collection
    {
        return UploadTambahan::query()
            ->with('items')
            ->where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();
    }

    public function findWithItems(int $id): ?UploadTambahan
    {
        return UploadTambahan::query()
            ->with('items')
            ->find($id);
    }
}
