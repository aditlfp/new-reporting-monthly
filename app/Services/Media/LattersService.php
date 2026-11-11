<?php

namespace App\Services\Media;

use App\Repositories\Contracts\LattersRepositoryInterface;
use Illuminate\Http\UploadedFile;

class LattersService
{
    public function __construct(
        private readonly LattersRepositoryInterface $repository,
        private readonly LetterStorageService $storage,
    ) {}

    public function indexData(?array $allowedClientIds = null): array
    {
        return [
            'letters' => $allowedClientIds === null
                ? $this->repository->paginateWithCoverClient()
                : $this->repository->paginateWithCoverClient($allowedClientIds),
            'covers' => $allowedClientIds === null
                ? $this->repository->getAllCovers()
                : $this->repository->getAllCovers($allowedClientIds),
        ];
    }

    public function store(array $validated, ?UploadedFile $signature, ?array $allowedClientIds = null)
    {
        $this->assertCoverAllowed((int) $validated['cover_id'], $allowedClientIds);
        $storedSignature = $this->storage->storeSignature($signature);

        if ($storedSignature) {
            $validated['signature'] = $storedSignature;
        }

        return $this->repository->create($validated);
    }

    public function showById(int $id, ?array $allowedClientIds = null)
    {
        return $allowedClientIds === null
            ? $this->repository->findWithCoverClientOrFail($id)
            : $this->repository->findWithCoverClientOrFail($id, $allowedClientIds);
    }

    public function update(int $id, array $validated, ?UploadedFile $signature = null, ?array $allowedClientIds = null)
    {
        $latters = $allowedClientIds === null
            ? $this->repository->findWithCoverClientOrFail($id)
            : $this->repository->findWithCoverClientOrFail($id, $allowedClientIds);
        $this->assertCoverAllowed((int) $validated['cover_id'], $allowedClientIds);
        $storedSignature = $this->storage->storeSignature($signature);

        if ($storedSignature) {
            $validated['signature'] = $storedSignature;
        }

        return $this->repository->update($latters, $validated);
    }

    public function destroy(int $id, ?array $allowedClientIds = null): bool
    {
        if ($allowedClientIds !== null) {
            $this->repository->findWithCoverClientOrFail($id, $allowedClientIds);
        }

        return $this->repository->deleteById($id);
    }

    private function assertCoverAllowed(int $coverId, ?array $allowedClientIds): void
    {
        if ($allowedClientIds === null) {
            return;
        }

        $coverAllowed = $this->repository->getAllCovers($allowedClientIds)
            ->contains(fn ($cover) => (int) $cover->id === $coverId);

        if (! $coverAllowed) {
            abort(403, 'Mitra tidak diizinkan.');
        }
    }
}
