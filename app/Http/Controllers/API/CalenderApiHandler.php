<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\UploadImage;
use App\Models\User;
use App\Services\HolidayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CalenderApiHandler extends Controller
{
    
    public function getCalendarData(HolidayService $holidayService)
    {
        $holidays = $holidayService->getHolidays(); 

        return response()->json($holidays);
    }

    public function modalShow(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'client_id' => 'required|integer',
        ]);

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
                        ->whereHas('kerjasama.client', function ($q) use ($user) {
                                    $q->where('id', $user->kerjasama->client->id);
                            })->get();

        $uploads = UploadImage::with('user:id,nama_lengkap,name')
            ->whereIn('user_id', $userIds)
            ->where('clients_id', $request->client_id)
            ->whereDate('created_at', $request->date)
            ->get()
            ->map(function ($item) {
                $item->created_at_formatted =
                    $item->created_at->translatedFormat('d F Y H:i');
                return $item;
            });

        return response()->json([
            'status' => true,
            'message' => "Get data by date and clients_id",
            'data' => $uploads,
        ]);
    }
}
