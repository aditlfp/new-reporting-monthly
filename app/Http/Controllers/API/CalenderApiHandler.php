<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class CalenderApiHandler extends Controller
{
    
    public function getCalendarData(HolidayService $holidayService)
    {
        $holidays = $holidayService->getHolidays(); 

        return response()->json($holidays);
    }
}
