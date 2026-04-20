<?php

namespace App\Repositories;

use App\Models\Settings;
use App\Repositories\Contracts\SettingsRepositoryInterface;

class EloquentSettingsRepository implements SettingsRepositoryInterface
{
    public function upsert(array $attributes): Settings
    {
        return Settings::query()->updateOrCreate(
            ['id' => 1],
            $attributes,
        );
    }
}
