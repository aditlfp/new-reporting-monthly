<?php

namespace App\Services\Shared;

use App\Models\User;
use App\Repositories\Contracts\RoleScopeRepositoryInterface;
use Illuminate\Support\Str;

class RoleScopeService
{
    public function __construct(
        private readonly RoleScopeRepositoryInterface $repository,
    ) {}

    public function allowedTypesForUser(User $user): array
    {
        $typeJabatanUser = Str::upper((string) ($user->jabatan?->name_jabatan ?? ''));
        $typeJabatanUser = str_replace('pusat', 'PUSAT', $typeJabatanUser);
        $isSecurity = Str::contains($typeJabatanUser, 'SUPERVISOR PUSAT SECURITY');

        if (!$isSecurity && $typeJabatanUser === 'DANRU SECURITY') {
            return ['SECURITY'];
        }

        if ($isSecurity) {
            return ['SECURITY', 'SUPERVISOR PUSAT SECURITY'];
        }

        return ['CLEANING SERVICE', 'FRONT OFFICE', 'LEADER', 'FO', 'KASIR', 'KARYAWAN', 'TAMAN', 'TEKNISI'];
    }

    public function allowedUserIds(User $user, ?int $clientId = null): array
    {
        $types = $this->allowedTypesForUser($user);
        $jabatanIds = $this->repository->getJabatanIdsByTypes($types);

        return $this->repository->getUserIdsByJabatanAndClient(
            $jabatanIds,
            $clientId ?? (int) ($user->kerjasama?->client_id ?: 0),
        );
    }
}
