<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use App\Models\UserSettings;
use App\Services\CalendarService;
use App\Services\HolidayService;
use App\Services\UploadImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserNavigateController extends Controller
{
    private $auth;
    private $now;

    public function __construct()
    {
        $this->auth = auth()->user();
        $this->now = now();
    }

    public function toUploadImgLaporan()
    {
        
        $service = new UploadImageService($this->auth);
        $data = $service->getUploadImageData();

        return view('pages.user.send_img.create', $data);
    }

    public function toCalenderUpload(HolidayService $holidayService)
    {
        $holidays = $holidayService->getHolidays();
        $serviceGetCalendar = new CalendarService($this->auth);
        $data = $serviceGetCalendar->getCalendarData();

        return view('pages.user.calender.index', [
            'holidays' => $holidays,
            'translate' => $data['translate'],
            'uploadsByDay' => $data['uploadsByDay']
        ]);
    }

    public function toSettings()
    {
        $dataSetting = UserSettings::where('user_id', $this->auth->id)->first();
        return view('pages.user.settings.index', compact('dataSetting'));
    }
}
