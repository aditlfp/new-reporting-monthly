<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UploadImage;
use App\Services\HolidayService;
use Illuminate\Http\Request;

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

        $uploads = UploadImage::with('user:id,nama_lengkap,name')->where('clients_id', $request->client_id)
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
