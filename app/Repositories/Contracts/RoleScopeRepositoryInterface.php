<?php

namespace App\Repositories\Contracts;

interface RoleScopeRepositoryInterface
{
    public function getJabatanIdsByTypes(array $types): array;

    public function getUserIdsByJabatanAndClient(array $jabatanIds, ?int $clientId = null): array;
}
