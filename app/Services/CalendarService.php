<?php

namespace App\Services;

use App\Repositories\Contracts\MonitoringRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\Shared\RoleScopeService;

class CalendarService
{
    protected Authenticatable $user;

    public function __construct(
        Authenticatable $user,
        private readonly RoleScopeService $roleScope,
        private readonly MonitoringRepositoryInterface $monitoring,
    ) {
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

        $clientId = (int) $this->user->kerjasama->client_id;
        $userIds = $this->roleScope->allowedUserIds($this->user, $clientId);
        $uploadsByDay = $this->monitoring->getUploadDailyTotalsByClientAndUsers($clientId, $userIds);

        return [
            'translate' => $translate,
            'uploadsByDay' => $uploadsByDay
        ];
    }
}
