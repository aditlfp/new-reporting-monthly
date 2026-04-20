<?php

namespace App\Services;

use App\Models\Clients;
use App\Models\User;
use App\Repositories\Contracts\UploadTambahanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

class UploadTambahanService
{
    public function __construct(
        private readonly UploadTambahanRepositoryInterface $repository,
        private readonly UploadTambahanStorageService $storageService,
    ) {}

    public function getUserIndexData(User $user): array
    {
        $this->ensureUserCanUpload($user);

        return [
            'uploads' => $this->repository->paginateByUser((int) $user->id),
        ];
    }

    public function createUpload(User $user, array $validated): \App\Models\UploadTambahan
    {
        $this->ensureUserCanUpload($user);

        $clientId = (int) ($user->kerjasama?->client_id ?? $user->clients_id ?? 0);
        if ($clientId <= 0) {
            throw new \RuntimeException('Client pengguna tidak valid.');
        }

        $now = now();
        $folder = 'upload_tambahan/' . $now->format('Y/m');
        $items = [];

        foreach ($validated['items'] as $item) {
            $tempToken = (string) $item['temp_token'];
            $payload = \App\Helpers\FileHelper::decodeTempUploadToken($tempToken);
            $filePath = $this->storageService->storeFromTempToken($tempToken, $folder);

            $items[] = [
                'file_path' => $filePath,
                'file_name' => (string) ($payload['original_name'] ?? basename($filePath)),
                'mime_type' => (string) ($payload['mime_type'] ?? 'application/octet-stream'),
                'file_size' => (int) ($payload['size'] ?? 0),
                'keterangan' => (string) $item['keterangan'],
            ];
        }

        return $this->repository->createWithItems([
            'user_id' => (int) $user->id,
            'clients_id' => $clientId,
        ], $items);
    }

    public function initChunkUpload(int $userId, array $validated): string
    {
        return $this->storageService->initChunkUpload(
            $userId,
            (string) $validated['file_name'],
            (int) $validated['file_size'],
            (string) $validated['mime_type'],
            (int) $validated['total_chunks'],
        );
    }

    public function storeChunkPart(int $userId, array $validated, \Illuminate\Http\Request $request): array
    {
        return $this->storageService->storeChunkPart(
            $userId,
            (string) $validated['upload_id'],
            (int) $validated['chunk_index'],
            file_get_contents($request->file('chunk')->getRealPath()),
        );
    }

    public function finalizeChunkUpload(int $userId, string $uploadId): array
    {
        return $this->storageService->finalizeChunkUpload($userId, $uploadId);
    }

    public function cancelChunkUpload(int $userId, ?string $tempToken, ?string $uploadId): void
    {
        $this->storageService->cancelChunkUpload($userId, $tempToken, $uploadId);
    }

    public function getCheckSummary(User $viewer, int $month, int $year): array
    {
        $this->ensureUserCanCheck($viewer);
        $users = $this->resolveUsersForCheck($viewer, null);
        $userIds = $users->pluck('id')->map(fn($id) => (int) $id)->all();
        $uploadCounts = $this->repository->countUploadsByUsers($userIds, $month, $year)->keyBy('user_id');

        $summary = $users->map(function ($user) use ($uploadCounts) {
            $count = (int) ($uploadCounts->get($user->id)->total_uploads ?? 0);

            return [
                'user_id' => (int) $user->id,
                'nama_lengkap' => (string) $user->nama_lengkap,
                'jabatan' => (string) ($user->jabatan->name_jabatan ?? '-'),
                'mitra' => (string) ($user->kerjasama->client->name ?? '-'),
                'total_uploads' => $count,
                'uploaded' => $count > 0,
            ];
        })->values();

        return [
            'month' => $month,
            'year' => $year,
            'data' => $summary,
        ];
    }

    public function getShowDetail(User $viewer, int $targetId): array
    {
        $this->ensureUserCanUpload($viewer);
        $clientIds = $this->resolveScopeClientIds($viewer);
        $users = $this->getScopedUploadUsers($clientIds, null, true)->keyBy('id');

        $upload = $this->repository->findWithItems($targetId);

        return [
            'upload' => $upload
        ];
    }

    public function getCheckDetail(User $viewer, int $targetUserId, int $month, int $year): array
    {
        $this->ensureUserCanCheck($viewer);
        $users = $this->resolveUsersForCheck($viewer, null)->keyBy('id');

        if (!$users->has($targetUserId)) {
            throw new \RuntimeException('User tidak termasuk cakupan akses.');
        }

        $targetUser = $users->get($targetUserId);
        $uploads = $this->repository->getByUserAndPeriod($targetUserId, $month, $year);

        return [
            'user' => [
                'id' => (int) $targetUser->id,
                'nama_lengkap' => (string) $targetUser->nama_lengkap,
                'jabatan' => (string) ($targetUser->jabatan->name_jabatan ?? '-'),
                'mitra' => (string) ($targetUser->kerjasama->client->name ?? '-'),
            ],
            'uploads' => $uploads,
        ];
    }

    public function getAdminIndexData(array $filters): array
    {
        $userIds = $this->getScopedUploadUsers(null, $filters['search'] ?? null, false)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $paginator = empty($userIds)
            ? $this->emptyPaginator()
            : $this->repository->paginateAdmin($filters, $userIds);

        $userMap = User::query()
            ->with(['kerjasama.client', 'jabatan'])
            ->whereIn('id', $paginator->getCollection()->pluck('user_id')->all())
            ->get()
            ->keyBy('id');

        $clientMap = Clients::query()
            ->whereIn('id', $paginator->getCollection()->pluck('clients_id')->all())
            ->get()
            ->keyBy('id');

        $paginator->setCollection(
            $paginator->getCollection()->map(function ($upload) use ($userMap, $clientMap) {
                $user = $userMap->get($upload->user_id);
                $client = $clientMap->get($upload->clients_id);
                $upload->uploader_name = (string) ($user->nama_lengkap ?? '-');
                $upload->uploader_jabatan = (string) ($user->jabatan->name_jabatan ?? '-');
                $upload->client_name = (string) ($client->name ?? '-');

                return $upload;
            })
        );

        return [
            'uploads' => $paginator,
            'clients' => Clients::query()->orderBy('name')->get(['id', 'name']),
        ];
    }

    public function getAdminDetail(int $id)
    {
        $upload = $this->repository->findWithItems($id);

        if (!$upload) {
            return null;
        }

        $user = User::query()->with(['jabatan', 'kerjasama.client'])->find($upload->user_id);
        $client = Clients::query()->find($upload->clients_id);

        return [
            'upload' => $upload,
            'user' => $user,
            'client' => $client,
        ];
    }

    public function resolvePeriod(?int $month, ?int $year): array
    {
        return [
            'month' => $month ?: (int) now()->month,
            'year' => $year ?: (int) now()->year,
        ];
    }

    public function ensureUserCanUpload(User $user): void
    {
        if (!$user->isAccess()) {
            throw new \RuntimeException('Akses upload tambahan tidak diizinkan.');
        }
    }

    public function ensureUserCanCheck(User $user): void
    {
        if (!$user->canAccess() || !$user->isSupervisorPusatOrManajemen()) {
            throw new \RuntimeException('Akses check upload tambahan tidak diizinkan.');
        }
    }

    public function isSupervisorWilayah(User $user): bool
    {
        $text = strtolower((string) ($user->jabatan?->type_jabatan . ' ' . $user->jabatan?->name_jabatan));

        return str_contains($text, 'supervisor wilayah') || str_contains($text, 'spv wilayah');
    }

    public function isSupervisorArea(User $user): bool
    {
        $text = strtolower((string) ($user->jabatan?->type_jabatan . ' ' . $user->jabatan?->name_jabatan));

        return str_contains($text, 'supervisor area') || str_contains($text, 'spv area');
    }

    /**
     * @return array<int>
     */
    protected function resolveScopeClientIds(User $viewer): array
    {
        $ownClientId = (int) ($viewer->kerjasama?->client_id ?? 0);
        if ($ownClientId <= 0) {
            return [];
        }

        if ($this->isSupervisorArea($viewer)) {
            return [$ownClientId];
        }

        if ($this->isSupervisorWilayah($viewer)) {
            $baseClient = Clients::query()->find($ownClientId);
            $baseAddress = strtolower(trim((string) ($baseClient?->address ?? '')));
            if ($baseAddress === '') {
                return [$ownClientId];
            }

            return Clients::query()
                ->where(function ($query) use ($baseAddress) {
                    $query->whereRaw('LOWER(address) LIKE ?', ['%' . $baseAddress . '%'])
                        ->orWhereRaw('? LIKE CONCAT("%", LOWER(address), "%")', [$baseAddress]);
                })
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();
        }

        return [$ownClientId];
    }

    protected function resolveUsersForCheck(User $viewer, ?string $search = null): Collection
    {
        if ($this->isSpecialSpvPusatViewer($viewer)) {
            $targetJabatanNames = $this->resolveSpecialSpvPusatTargetJabatanNames($viewer);
            if (!empty($targetJabatanNames)) {
                return $this->getScopedUploadUsers(
                    null,
                    $search,
                    false,
                    $targetJabatanNames,
                );
            }
        }

        $clientIds = $this->resolveScopeClientIds($viewer);

        return $this->getScopedUploadUsers($clientIds, $search, true);
    }

    protected function isSpecialSpvPusatViewer(User $viewer): bool
    {
        $text = strtolower(trim((string) ($viewer->jabatan?->type_jabatan . ' ' . $viewer->jabatan?->name_jabatan)));

        return $viewer->isSupervisorPusatOrManajemen()
            && (str_contains($text, 'supervisor pusat') || str_contains($text, 'spv pusat'));
    }

    /**
     * @return array<int, string>
     */
    protected function resolveSpecialSpvPusatTargetJabatanNames(User $viewer): array
    {
        $code = strtoupper(trim((string) ($viewer->jabatan?->code_jabatan ?? '')));

        return match ($code) {
            'SPV' => ['LEADER CS', 'LEADER'],
            'SPV-W' => ['DANRU SECURITY'],
            default => [],
        };
    }

    /**
     * @param array<int>|null $clientIds
     * @param array<int, string>|null $exactJabatanNames
     */
    protected function getScopedUploadUsers(?array $clientIds, ?string $search = null, bool $excludePusatManajemen = false, ?array $exactJabatanNames = null): Collection
    {
        $query = User::query()
            ->with(['jabatan', 'kerjasama.client'])
            ->whereHas('kerjasama', function ($q) use ($clientIds) {
                if (!empty($clientIds)) {
                    $q->whereIn('client_id', $clientIds);
                }
            })
            ->when(!empty($search), function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ['%' . strtolower(trim((string) $search)) . '%']);
            });

        if (!empty($exactJabatanNames)) {
            $normalizedNames = array_values(array_filter(array_map(
                fn($name) => strtoupper(trim((string) $name)),
                $exactJabatanNames
            )));

            if (!empty($normalizedNames)) {
                $query->whereHas('jabatan', function ($q) use ($normalizedNames) {
                    $q->where(function ($inner) use ($normalizedNames) {
                        foreach ($normalizedNames as $name) {
                            $inner->orWhereRaw('UPPER(TRIM(COALESCE(name_jabatan, ""))) = ?', [$name]);
                        }
                    });
                });
            }
        } else {
            $query->whereHas('jabatan', function ($q) {
                $q->where(function ($inner) {
                    $inner
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%leader%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%manajemen%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%supervisor wilayah%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%supervisor area%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%supervisor pusat%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%spv wilayah%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%spv area%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%spv pusat%'])
                        ->orWhereIn(\Illuminate\Support\Facades\DB::raw('UPPER(COALESCE(code_jabatan, ""))'), ['CO-CS', 'CO-SCR']);
                });
            });
        }

        if ($excludePusatManajemen) {
            $query->whereDoesntHave('jabatan', function ($q) {
                $q->where(function ($inner) {
                    $inner
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%supervisor pusat%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%spv pusat%'])
                        ->orWhereRaw('LOWER(CONCAT(COALESCE(type_jabatan, ""), " ", COALESCE(name_jabatan, ""))) LIKE ?', ['%manajemen%']);
                });
            });
        }

        return $query->get();
    }

    protected function emptyPaginator(int $perPage = 20): LengthAwarePaginator
    {
        return new Paginator([], 0, $perPage, 1, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}
