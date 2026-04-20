<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface AbsensiUserRepositoryInterface
{
    public function getUsersWithPosition(): Collection;
}
