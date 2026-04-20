<?php

namespace App\Repositories\Contracts;

use App\Models\UserSettings;

interface UserSettingsRepositoryInterface
{
    public function findByUserId(int $userId): ?UserSettings;

    public function upsertTheme(int $userId, array $theme): UserSettings;
}
