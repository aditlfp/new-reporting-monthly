<?php

namespace App\Repositories;

use App\Models\UserSettings;
use App\Repositories\Contracts\UserSettingsRepositoryInterface;

class EloquentUserSettingsRepository implements UserSettingsRepositoryInterface
{
    public function findByUserId(int $userId): ?UserSettings
    {
        return UserSettings::query()->where('user_id', $userId)->first();
    }

    public function upsertTheme(int $userId, array $theme): UserSettings
    {
        return UserSettings::query()->updateOrCreate(
            ['user_id' => $userId],
            ['data_theme' => $theme],
        );
    }
}
