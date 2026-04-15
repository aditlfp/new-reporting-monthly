<?php

namespace App\Repositories;

use App\Models\Clients;
use App\Models\UploadImage;
use App\Models\User;
use App\Repositories\Contracts\UploadImageRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class EloquentUploadImageRepository implements UploadImageRepositoryInterface
{
    public function paginateUserUploads(User $user, int $perPage = 14): LengthAwarePaginator
    {
        return UploadImage::query()
            ->where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    public function findLatestUserDraft(User $user): ?UploadImage
    {
        return UploadImage::query()
            ->where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->latest()
            ->first();
    }

    public function countUserDraftsForMonth(int $userId, CarbonInterface $date): int
    {
        return UploadImage::query()
            ->where('user_id', $userId)
            ->where('status', 0)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();
    }

    public function createUpload(array $attributes): UploadImage
    {
        return UploadImage::query()->create($attributes);
    }

    public function updateUpload(UploadImage $uploadImage, array $attributes): UploadImage
    {
        $uploadImage->update($attributes);

        return $uploadImage->fresh();
    }

    public function deleteUpload(UploadImage $uploadImage): void
    {
        $uploadImage->delete();
    }

    public function findUserOwnedUpload(int $uploadId, User $user): UploadImage
    {
        $query = UploadImage::query()->whereKey($uploadId)->where('user_id', $user->id);

        if (!empty($user->kerjasama?->client_id)) {
            $query->where('clients_id', $user->kerjasama->client_id);
        } else {
            $query->where('clients_id', $user->clients_id);
        }

        $uploadImage = $query->first();

        if (!$uploadImage) {
            throw (new ModelNotFoundException())->setModel(UploadImage::class, [$uploadId]);
        }

        return $uploadImage;
    }

    public function paginateAdminUploads(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = UploadImage::query()->with(['clients', 'user.kerjasama']);

        if (!empty($filters['mitra'])) {
            $query->where('clients_id', $filters['mitra']);
        }

        if (!empty($filters['user'])) {
            $query->where('user_id', $filters['user']);
            $perPage = 30;
        } elseif (!empty($filters['mitra'])) {
            $perPage = 14;
        }

        if (!empty($filters['month'])) {
            $query->whereMonth('created_at', $filters['month']);
            if (!empty($filters['year'])) {
                $query->whereYear('created_at', $filters['year']);
            }
        }

        return $query->latest()->paginate($perPage);
    }

    public function findAdminUploadById(int $uploadId): ?UploadImage
    {
        return UploadImage::query()->with(['clients', 'user.kerjasama'])->find($uploadId);
    }

    public function getPdfDataset(?array $ids = null, ?string $month = null): Collection
    {
        $query = UploadImage::query()->with('clients');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif (!empty($month)) {
            $date = Carbon::parse($month);
            $query->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

        return $query->oldest()->get();
    }

    public function getUsersByMitra(int $mitraId): Collection
    {
        return User::query()
            ->whereHas('kerjasama', function ($query) use ($mitraId) {
                $query->where('client_id', $mitraId);
            })
            ->get();
    }

    public function getMonthlyClientApprovedImages(int $clientId, CarbonInterface $date): Collection
    {
        return UploadImage::query()
            ->where('clients_id', $clientId)
            ->where('status', 1)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->get(['img_before', 'img_proccess', 'img_final']);
    }
}
