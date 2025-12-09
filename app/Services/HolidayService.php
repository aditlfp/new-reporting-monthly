<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HolidayService
{
    private $url;
    private $key;

    public function __construct()
    {
        $this->url = config('services.calendar.url');
        $this->key = config('services.calendar.key');
    }

    /**
     * Main function to get holiday data (cached yearly).
     */
    public function getHolidays()
    {
        $years = [
            Carbon::now()->year - 1,
            Carbon::now()->year,
            Carbon::now()->year + 1
        ];

        $allData = [];

        foreach ($years as $year) {
            $allData[$year] = $this->getYearlyData($year);
        }

        return $allData;
    }

    /**
     * Get holiday data by year with caching.
     */
    private function getYearlyData($year)
    {
        $fileName = "holidays/holidays_$year.json";

        // If file exists, return it (no API hit)
        if (Storage::exists($fileName)) {
            return json_decode(Storage::get($fileName), true);
        }

        // Otherwise fetch from API
        $response = Http::withHeaders([
            'x-api-co-id' => "{$this->key}",
        ])->get($this->url . "?year=" . $year);


        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        // Save to JSON for future use
        Storage::put($fileName, json_encode($data, JSON_PRETTY_PRINT));

        return $data;
    }
}
