<?php

namespace App\Services\Monitoring;

use App\Models\FixedImage;
use App\Repositories\Contracts\AbsensiUserRepositoryInterface;
use App\Repositories\Contracts\MonitoringRepositoryInterface;

class SendImageStatusService
{
    public function __construct(
        private readonly MonitoringRepositoryInterface $repository,
        private readonly AbsensiUserRepositoryInterface $absensiRepository,
    ) {}

    public function indexData(array $filters): array
    {
        $uploads = $this->repository->getUploadsAggregate(
            isset($filters['month']) && $filters['month'] !== '' ? (int) $filters['month'] : null,
            isset($filters['client_id']) && $filters['client_id'] !== '' ? (int) $filters['client_id'] : null,
        );

        $absensiUsersIndexed = $this->absensiRepository->getUsersWithPosition()->keyBy('id');

        foreach ($uploads as $upload) {
            $user = $absensiUsersIndexed[$upload->user_id] ?? null;
            $upload->user = $user;
            $upload->divisi = $user->nama_divisi ?? '-';
            $upload->jabatan = $user->name_jabatan ?? '-';
            $upload->has_uploaded_today = $upload->today_count > 0;
        }

        return [
            'uploads' => $uploads,
            'months' => $this->monthOptions(),
            'clients' => $this->repository->getAllClients(),
        ];
    }

    public function showData(int $id, int $client, int $month, int $year): array
    {
        $uploadsAll = $this->repository->getUploadsByUserClientMonthYear($id, $client, $month, $year);

        $jabatan = strtolower((string) optional(
            $uploadsAll->first()?->user?->divisi?->jabatan
        )->name_jabatan);

        if ($jabatan) {
            if (str_contains($jabatan, 'clean')) {
                $uploadsAll = $uploadsAll->filter(function ($item) {
                    $jab = strtolower((string) optional($item->user->divisi->jabatan)->name_jabatan);
                    return !str_contains($jab, 'secu') && !str_contains($jab, 'scur') && !str_contains($jab, 'sekur');
                });
            } else {
                $uploadsAll = $uploadsAll->filter(function ($item) {
                    $jab = strtolower((string) optional($item->user->divisi->jabatan)->name_jabatan);
                    return !str_contains($jab, 'clean');
                });
            }
        }

        return [
            'UploadsAll' => $uploadsAll,
            'fixed' => $this->repository->getFixedByClientMonthYear($client, $month, $year),
        ];
    }

    public function detailFixed(int $userId, string|int $month, string|int $year): array
    {
        $fixed = $this->repository->getFixedDetailByUserPeriod($userId, $month, $year);

        $data = $fixed->map(function (FixedImage $item) {
            return [
                'id' => $item->id,
                'upload_image_id' => $item->upload_image_id,
                'user_id' => $item->user_id,
                'clients_id' => $item->clients_id,
                'created_at' => optional($item->created_at)->toISOString(),
                'user_name' => $item->user?->nama_lengkap,
                'user_email' => $item->user?->email,
                'client_name' => $item->clients?->name,
                'client_address' => $item->clients?->address,
                'upload_note' => $item->uploadImage?->note,
                'upload_created_at' => optional($item->uploadImage?->created_at)->toISOString(),
                'upload_user_name' => $item->uploadImage?->user?->nama_lengkap,
                'upload_user_email' => $item->uploadImage?->user?->email,
                'upload_images' => [
                    'before' => $item->uploadImage?->img_before,
                    'process' => $item->uploadImage?->img_proccess,
                    'final' => $item->uploadImage?->img_final,
                ],
            ];
        })->values();

        return $data->all();
    }

    private function monthOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Januari'],
            ['value' => 2, 'label' => 'Februari'],
            ['value' => 3, 'label' => 'Maret'],
            ['value' => 4, 'label' => 'April'],
            ['value' => 5, 'label' => 'Mei'],
            ['value' => 6, 'label' => 'Juni'],
            ['value' => 7, 'label' => 'Juli'],
            ['value' => 8, 'label' => 'Agustus'],
            ['value' => 9, 'label' => 'September'],
            ['value' => 10, 'label' => 'Oktober'],
            ['value' => 11, 'label' => 'November'],
            ['value' => 12, 'label' => 'Desember'],
        ];
    }
}
