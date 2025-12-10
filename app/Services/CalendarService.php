<?php

namespace App\Services;

use App\Models\UploadImage;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class CalendarService
{
    protected $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function getCalendarData(): array
    {
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

        $clientId = $this->user->kerjasama->client_id;

        $uploadsByDay = UploadImage::selectRaw("
                DATE(created_at) as date,
                COUNT(*) as total
            ")
            ->where('clients_id', $clientId)
            ->where('status', 1)
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy(DB::raw("DATE(created_at)"))
            ->get();
        return [
            'translate' => $translate,
            'uploadsByDay' => $uploadsByDay
        ];
    }
}
