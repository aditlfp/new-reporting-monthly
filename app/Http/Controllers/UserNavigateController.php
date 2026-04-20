<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use App\Services\HolidayService;
use App\Services\Settings\UserSettingsService;
use App\Services\UploadImageService;

class UserNavigateController extends Controller
{
    public function __construct(
        private readonly UserSettingsService $userSettingsService,
    ) {}

    public function toUploadImgLaporan(UploadImageService $service)
    {
        $data = $service->getUploadImageData();

        return view('pages.user.send_img.create', $data);
    }

    public function toCalenderUpload(HolidayService $holidayService, CalendarService $calendarService)
    {
        $holidays = $holidayService->getHolidays();
        $data = $calendarService->getCalendarData();

        return view('pages.user.calender.index', [
            'holidays' => $holidays,
            'translate' => $data['translate'],
            'uploadsByDay' => $data['uploadsByDay'],
        ]);
    }

    public function toSettings()
    {
        $dataSetting = $this->userSettingsService->getByUser((int) auth()->id());

        return view('pages.user.settings.index', compact('dataSetting'));
    }
}
