<?php

namespace App\Repositories\Contracts;

use App\Models\UploadImage;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UploadImageRepositoryInterface
{
    public function paginateUserUploads(User $user, int $perPage = 14): LengthAwarePaginator;

    public function findLatestUserDraft(User $user): ?UploadImage;

    public function countUserDraftsForMonth(int $userId, CarbonInterface $date): int;

    public function createUpload(array $attributes): UploadImage;

    public function updateUpload(UploadImage $uploadImage, array $attributes): UploadImage;

    public function deleteUpload(UploadImage $uploadImage): void;

    public function findUserOwnedUpload(int $uploadId, User $user): UploadImage;

    public function paginateAdminUploads(array $filters, int $perPage = 20): LengthAwarePaginator;

    public function findAdminUploadById(int $uploadId): ?UploadImage;

    public function getPdfDataset(?array $ids = null, ?string $month = null): Collection;

    public function getUsersByMitra(int $mitraId): Collection;

    public function getMonthlyClientApprovedImages(int $clientId, CarbonInterface $date): Collection;
}
