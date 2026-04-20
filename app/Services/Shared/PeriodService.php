<?php

namespace App\Services\Shared;

use Carbon\Carbon;

class PeriodService
{
    public function monthRange(?int $month = null, ?int $year = null): array
    {
        $resolvedMonth = $month ?: now()->month;
        $resolvedYear = $year ?: now()->year;

        $startAt = Carbon::create($resolvedYear, $resolvedMonth, 1)->startOfMonth();
        $endAt = (clone $startAt)->endOfMonth();

        return [
            'month' => $resolvedMonth,
            'year' => $resolvedYear,
            'start_at' => $startAt,
            'end_at' => $endAt,
        ];
    }
}
