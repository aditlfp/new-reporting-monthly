<?php

namespace App\Services\Monitoring;

use App\Models\UploadImage;
use App\Repositories\Contracts\MonitoringRepositoryInterface;
use App\Services\Shared\PeriodService;
use App\Services\Shared\RoleScopeService;
use Exception;
use Illuminate\Http\Request;

class FixedImageService
{
    public function __construct(
        private readonly MonitoringRepositoryInterface $repository,
        private readonly RoleScopeService $roleScope,
        private readonly PeriodService $periodService,
    ) {}

    public function indexData(): array
    {
        return [
            'clients' => $this->repository->getClientsLite(),
        ];
    }

    public function createData(Request $request): array
    {
        $scope = $this->resolveScope($request);

        return [
            'client' => $this->repository->findClientBasic($scope['client_id']),
            'image' => $this->repository->paginateUploadsForFixed(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids'],
            ),
            'fixed' => $this->repository->getFixedForScope(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids'],
            ),
            'counts' => $this->buildCounts($scope),
        ];
    }

    public function setImage(array $data, Request $request): array
    {
        try {
            $scope = $this->resolveScope($request);
            $uploadImageId = (int) ($data['upload_image_id'] ?? 0);

            if ($uploadImageId <= 0) {
                return [
                    'success' => false,
                    'limit' => false,
                    'message' => 'Upload image tidak valid.',
                ];
            }

            $uploadImage = $this->resolveUploadImageInScope($uploadImageId, $scope);
            if (!$uploadImage) {
                return [
                    'success' => false,
                    'limit' => false,
                    'message' => 'Upload image tidak ditemukan atau tidak termasuk cakupan akses.',
                ];
            }

            $clientId = (int) $uploadImage->clients_id;
            $userId = (int) $uploadImage->user_id;

            $countUpload = $this->repository->countUserFixedThisMonth($clientId, $userId, $scope['start_at'], $scope['end_at']);

            if ($countUpload >= 11) {
                return [
                    'success' => false,
                    'limit' => true,
                    'message' => 'Limit Memilih Foto Tercapai!',
                ];
            }

            $existing = $this->repository->findFixedByUploadImageIdForScope(
                $uploadImageId,
                $clientId,
                $scope['allowed_user_ids'],
            );
            $model = $existing ?: $this->repository->createFixed([
                'user_id' => $userId,
                'clients_id' => $clientId,
                'upload_image_id' => $uploadImageId,
            ]);

            return [
                'success' => true,
                'limit' => false,
                'message' => $existing ? 'Data sudah dipilih sebelumnya.' : 'Data Has Been Saved!',
                'data' => $model,
                'counts' => $this->buildCounts($scope),
            ];
        } catch (Exception $e) {
            throw new Exception('Error Processing Request: ' . $e->getMessage());
        }
    }

    public function removeSelection(int $uploadImageId, Request $request): array
    {
        $scope = $this->resolveScope($request);
        $uploadImage = $this->resolveUploadImageInScope($uploadImageId, $scope);

        if (!$uploadImage) {
            return [
                'status' => false,
                'message' => 'Data tidak ditemukan atau tidak termasuk cakupan akses',
            ];
        }

        $deleted = $this->repository->deleteFixedByUploadImageIdForScope(
            $uploadImageId,
            (int) $uploadImage->clients_id,
            $scope['allowed_user_ids'],
        );

        if (!$deleted) {
            return [
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ];
        }

        return [
            'status' => true,
            'message' => 'Data berhasil dihapus',
            'counts' => $this->buildCounts($scope),
        ];
    }

    public function countFixed(Request $request): array
    {
        $scope = $this->resolveScope($request);
        $counts = $this->buildCounts($scope);

        return [
            'count' => $counts['total_fixed'],
            'count_today' => $counts['count_today'],
        ];
    }

    private function resolveScope(Request $request): array
    {
        $user = $request->user();
        $requestedClientId = $request->input('client_id');
        $fallbackClientId = $user?->kerjasama?->client_id;
        $clientId = (int) $fallbackClientId;

        if (!empty($requestedClientId) && method_exists($user, 'canAccess') && $user->canAccess()) {
            $clientId = (int) $requestedClientId;
        }

        $period = $this->periodService->monthRange(
            $request->filled('month') ? (int) $request->input('month') : null,
            $request->filled('year') ? (int) $request->input('year') : null,
        );

        return [
            'client_id' => $clientId,
            'start_at' => $period['start_at'],
            'end_at' => $period['end_at'],
            'allowed_user_ids' => $this->roleScope->allowedUserIds($user, $clientId),
        ];
    }

    private function buildCounts(array $scope): array
    {
        return [
            'total_fixed' => $this->repository->countFixedForUploadScope(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids'],
                false,
            ),
            'count_today' => $this->repository->countFixedForUploadScope(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids'],
                true,
            ),
        ];
    }

    private function resolveUploadImageInScope(int $uploadImageId, array $scope): ?UploadImage
    {
        return UploadImage::query()
            ->where('id', $uploadImageId)
            ->where('clients_id', $scope['client_id'])
            ->where('status', 1)
            ->whereBetween('created_at', [$scope['start_at'], $scope['end_at']])
            ->whereIn('user_id', $scope['allowed_user_ids'])
            ->first();
    }
}
