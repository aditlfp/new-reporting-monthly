<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarModalShowRequest;
use App\Services\HolidayService;
use App\Services\Monitoring\CalendarModalService;

class CalenderApiHandler extends Controller
{
    public function getCalendarData(HolidayService $holidayService)
    {
        $holidays = $holidayService->getHolidays();

        return response()->json($holidays);
    }

    public function modalShow(CalendarModalShowRequest $request, CalendarModalService $service)
    {
        return response()->json([
            'status' => true,
            'message' => 'Get data by date and clients_id',
            'data' => $service->getModalData($request),
        ]);
    }
}
