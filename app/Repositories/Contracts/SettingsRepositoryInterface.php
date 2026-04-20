<?php

namespace App\Repositories\Contracts;

use App\Models\Settings;

interface SettingsRepositoryInterface
{
    public function upsert(array $attributes): Settings;
}
