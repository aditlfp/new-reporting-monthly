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

    public function indexData(): array
    {
        return [
            'letters' => $this->repository->paginateWithCoverClient(),
            'covers' => $this->repository->getAllCovers(),
        ];
    }

    public function store(array $validated, ?UploadedFile $signature)
    {
        $storedSignature = $this->storage->storeSignature($signature);

        if ($storedSignature) {
            $validated['signature'] = $storedSignature;
        }

        return $this->repository->create($validated);
    }

    public function showById(int $id)
    {
        return $this->repository->findWithCoverClientOrFail($id);
    }

    public function update(int $id, array $validated, ?UploadedFile $signature = null)
    {
        $latters = $this->repository->findWithCoverClientOrFail($id);
        $storedSignature = $this->storage->storeSignature($signature);

        if ($storedSignature) {
            $validated['signature'] = $storedSignature;
        }

        return $this->repository->update($latters, $validated);
    }

    public function destroy(int $id): bool
    {
        return $this->repository->deleteById($id);
    }
}
