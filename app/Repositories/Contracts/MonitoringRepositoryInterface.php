<?php

namespace App\Repositories\Contracts;

use App\Models\FixedImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MonitoringRepositoryInterface
{
    public function getClientsLite(): Collection;

    public function getAllClients(): Collection;

    public function getFixedGroupedSummary(int $month, int $year): Collection;

    public function getClientNameById(int $id): ?string;

    public function countUploadsForMonth(int $year, int $month): int;

    public function latestActivities(int $limit = 7): Collection;

    public function countSessionsForMonth(int $month): int;

    public function getUserPerformanceByMonth(int $userId, int $year): Collection;

    public function getUserDrafts(int $userId, int $month, int $year): Collection;

    public function getApprovedUploadsByClient(int $clientId, int $month, int $year): Collection;

    public function countFixedByClientMonth(int $clientId, int $month, int $year): int;

    public function findClientBasic(int $clientId);

    public function paginateUploadsForFixed(int $clientId, $startAt, $endAt, array $allowedUserIds, int $perPage = 6): LengthAwarePaginator;

    public function getFixedForScope(int $clientId, $startAt, $endAt, array $allowedUserIds): Collection;

    public function countFixedForUploadScope(int $clientId, $startAt, $endAt, array $allowedUserIds, bool $todayOnly = false): int;

    public function countUserFixedThisMonth(int $clientId, int $userId, $startAt, $endAt): int;

    public function findFixedByUploadImageId(int $uploadImageId): ?FixedImage;
    public function findFixedByUploadImageIdForScope(int $uploadImageId, int $clientId, array $allowedUserIds): ?FixedImage;

    public function createFixed(array $payload): FixedImage;

    public function deleteFixedByUploadImageId(int $uploadImageId): bool;
    public function deleteFixedByUploadImageIdForScope(int $uploadImageId, int $clientId, array $allowedUserIds): bool;

    public function getClientsByProvince(string $province): Collection;

    public function getUsersByJabatanNameLike(string $keyword): Collection;

    public function getUsersWithCountByIds(array $userIds, ?int $clientId, int $month, int $year): Collection;

    public function getTodayCountByUsers(array $userIds): Collection;

    public function getUserBasic(int $id);

    public function getMitraByClientId(int $clientId);

    public function getFixedByUserBetween(int $id, $startAt, $endAt): Collection;

    public function getUploadsAggregate(?int $month, ?int $clientId): LengthAwarePaginator;

    public function getUserByIdWithDivisi(int $id);

    public function getUploadsByUserClientMonthYear(int $id, int $client, int $month, int $year): Collection;

    public function getFixedByClientMonthYear(int $client, int $month, int $year): Collection;

    public function getFixedDetailByUserPeriod(int $userId, string|int $month, string|int $year): Collection;

    public function getUploadsByDateAndClient(array $userIds, int $clientId, string $date): Collection;

    public function getUploadDailyTotalsByClientAndUsers(int $clientId, array $userIds): Collection;
}
