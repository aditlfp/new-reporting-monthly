<?php

namespace App\Services\Settings;

use App\Models\UserSettings;
use App\Repositories\Contracts\UserSettingsRepositoryInterface;

class UserSettingsService
{
    public function __construct(
        private readonly UserSettingsRepositoryInterface $repository,
    ) {}

    public function getByUser(int $userId): ?UserSettings
    {
        return $this->repository->findByUserId($userId);
    }

    public function storeTheme(int $userId, array $theme): UserSettings
    {
        return $this->repository->upsertTheme($userId, $theme);
    }
}
