<?php

namespace App\Services\Media;

use App\Repositories\Contracts\QrCodeRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService
{
    private const QR_TARGET_BASE_URL = 'https://laporan-sac.sac-po.com/send-img/laporan';

    public function __construct(
        private readonly QrCodeRepositoryInterface $repository,
        private readonly QrCodeStorageService $storage,
    ) {}

    public function indexData(string $search): array
    {
        return [
            'qrCodes' => $this->repository->paginate($search),
            'search' => $search,
        ];
    }

    public function create(string $data)
    {
        $targetUrl = self::QR_TARGET_BASE_URL . '?n=' . rawurlencode($data);
        $filename = 'qr/' . Str::uuid() . '.png';
        $qrImage = QrCodeGenerator::format('png')
            ->size(300)
            ->margin(1)
            ->generate($targetUrl);

        Storage::disk('public')->put($filename, $qrImage);

        return $this->repository->create([
            'qr' => $filename,
            'data' => $data,
        ]);
    }

    public function update(int $id, string $data)
    {
        $qrCode = $this->repository->findOrFail($id);
        $targetUrl = self::QR_TARGET_BASE_URL . '?n=' . rawurlencode($data);

        $qrImage = QrCodeGenerator::format('png')
            ->size(300)
            ->margin(1)
            ->generate($targetUrl);

        Storage::disk('public')->put($qrCode->qr, $qrImage);

        return $this->repository->update($qrCode, [
            'data' => $data,
        ]);
    }

    public function getById(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function delete(int $id): void
    {
        $qrCode = $this->repository->findOrFail($id);
        $this->storage->delete($qrCode->qr);
        $this->repository->delete($qrCode);
    }
}
