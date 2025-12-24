<?php

namespace App\Services;

use App\Models\Jabatan;
use App\Models\UploadImage;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $user = auth()->user();
        $typeJabatanUser = Str::upper($user->jabatan->name_jabatan);

            // normalisasi typo
        $typeJabatanUser = str_replace('pusat', 'PUSAT', $typeJabatanUser);

        $isSecurity = Str::contains($typeJabatanUser, 'SUPERVISOR PUSAT SECURITY');

        if(!$isSecurity && $typeJabatanUser == 'DANRU SECURITY')
        {
            $type = ['SECURITY'];
        }else{
            $type = $isSecurity
                ? ['SECURITY', 'SUPERVISOR PUSAT SECURITY']
                : [
                    'CLEANING SERVICE',
                    'FRONT OFFICE',
                    'LEADER',
                    'FO',
                    'KASIR',
                    'KARYAWAN',
                    'TAMAN',
                    'TEKNISI'
                ];    
        }

            // dd($isSecurity, $type);

        $jabId = Jabatan::whereIn(
                DB::raw('UPPER(type_jabatan)'),
                $type
            )
            ->pluck('id')
            ->toArray();
        $userIds = User::select('id')->whereIn('jabatan_id', $jabId) 
                        ->whereHas('kerjasama.client', function ($q) use ($clientId) {
                                    $q->where('id', $clientId);
                            })->get();

        $uploadsByDay = UploadImage::selectRaw("
                        DATE(created_at) as date,
                        COUNT(*) as total
                    ")
                    ->where('clients_id', $clientId)
                    ->when(!empty($userIds), function ($q) use ($userIds) {
                        $q->whereIn('user_id', $userIds);
                    })
                    ->where('status', 1)
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy(DB::raw('DATE(created_at)'))
                    ->get();

        return [
            'translate' => $translate,
            'uploadsByDay' => $uploadsByDay
        ];
    }
}
