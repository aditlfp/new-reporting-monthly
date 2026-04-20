<?php

namespace App\Services\Settings;

use App\Repositories\Contracts\SettingsRepositoryInterface;

class SettingsService
{
    public function __construct(
        private readonly SettingsRepositoryInterface $repository,
    ) {}

    public function store(array $payload): void
    {
        $this->repository->upsert($payload);
    }
}
