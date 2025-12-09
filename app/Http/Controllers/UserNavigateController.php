<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use App\Services\HolidayService;
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
        $uploadDraft = UploadImage::where('user_id', $this->auth->id)
                ->where('status', 0)
                ->whereMonth('created_at', $this->now->month)
                ->latest()
                ->first();
        $allImages = UploadImage::where('clients_id', $this->auth->kerjasama->client_id)
            ->where('status', 1)
            ->whereMonth('created_at', $this->now->month)
            ->latest()
            ->get();
        $imageCount = UploadImage::where('clients_id', $this->auth->kerjasama->client_id)
            ->whereMonth('created_at', $this->now>month)
            ->where('status', 1);

        // Count each image type separately
        $totalBefore = (clone $imageCount)
            ->whereNotNull('img_before')
            ->where('img_before', '!=', '')
            ->where('img_before', '!=', 'none')
            ->count();

        $totalProccess = (clone $imageCount)
            ->whereNotNull('img_proccess')
            ->where('img_proccess', '!=', '')
            ->where('img_proccess', '!=', 'none')
            ->count();

        $totalFinal = (clone $imageCount)
            ->whereNotNull('img_final')
            ->where('img_final', '!=', '')
            ->where('img_final', '!=', 'none')
            ->count();

        // Calculate the total number of images
        $totalImageCount = $totalBefore + $totalProccess + $totalFinal;

        return view('pages.user.send_img.create', compact('uploadDraft', 'totalImageCount', 'allImages'));
    }

    public function toCalenderUpload(HolidayService $holidayService)
    {
        $holidays = $holidayService->getHolidays();
        $translate = [
                "New Year's Day" => "Tahun Baru",
                "Chinese New Year" => "Tahun Baru Imlek",
                "Good Friday" => "Jumat Agung",
                "Labor Day" => "Hari Buruh",
                "Eid al-Fitr" => "Hari Raya Idul Fitri",
                "Idul Fitri Holiday" => "Hari Raya Idul Fitri",
                "Eid al-Adha" => "Hari Raya Idul Adha",
                "Islamic New Year" => "Tahun Baru Hijriah",
                "Christmas Day" => "Hari Raya Natal",
                "Ascension Day of Jesus Christ" => "Kenaikan Isa Almasih",
                "Waisak Day" => "Hari Raya Waisak",
                "Independence Day" => "Hari Kemerdekaan",
                "Ascension of the Prophet Muhammad" => "Isra Mi'raj Nabi Muhammad SAW",
                "Chinese New Year's Day" => "Hari Raya Imlek",
                "Bali's Day of Silence and Hindu New Year" => "Hari Raya Nyepi",
                "Easter Sunday" => "Kebangkitan Yesus Kristus / Paskah",
                "International Labor Day" => "Hari Buruh",
                "Muharram / Islamic New Year" => "1 Muharam Tahun Baru Islam",
                "Indonesian Independence Day" => "Hari Kemerdekaan Indonesia",
            ];

        $clientId = $this->auth->kerjasama->client_id;

        $uploadsByDay = UploadImage::selectRaw("
                DATE(created_at) as date,
                COUNT(*) as total
            ")
            ->where('clients_id', $clientId)
            ->where('status', 1)
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy(DB::raw("DATE(created_at)"))
            ->get();
        return view('pages.user.calender.index', compact('holidays', 'translate', 'uploadsByDay'));
    }
}
