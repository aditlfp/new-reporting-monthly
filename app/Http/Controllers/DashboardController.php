<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\UploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $user = Auth::user();
        $clientId = $user->kerjasama->client_id;
        $data = FixedImage::with([
                'user.divisi.jabatan', // ambil jabatan
                'clients'
            ])
            ->select(
                'clients_id',
                'user_id',
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->groupBy('clients_id', 'user_id', 'month', 'year')
            ->get();

            $summary = [];

            foreach ($data as $row) {
                $client = $row->clients_id;
                $jabatan = $row->user->divisi->jabatan->name_jabatan ?? 'Unknown';
                
                if (!isset($summary[$client][$jabatan])) {
                    $summary[$client][$jabatan] = 0;
                }

                // tambahkan upload-nya
                $summary[$client][$jabatan] += $row->total;
            }

            $result = [];

            foreach ($summary as $clientId => $jabList) {
                foreach ($jabList as $jabatan => $totalUpload) {

                    $quota = 11;
                    $percentage = $quota > 0
                        ? round(($totalUpload / $quota) * 100, 1)
                        : 0;

                    $result[] = [
                        'client' => Clients::find($clientId)->name ?? 'Unknown',
                        'jabatan' => $jabatan,
                        'uploads' => $totalUpload,
                        'quota' => $quota,
                        'percentage' => $percentage > 100 ? 100 : $percentage,
                    ];
                }
            }

        if ($user->role_id == 2) {

            // Total bulan ini
            $totalThisMonth = UploadImage::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();

            // Total bulan lalu
            $lastMonth = $now->copy()->subMonth();

            $totalLastMonth = UploadImage::whereYear('created_at', $lastMonth->year)
                ->whereMonth('created_at', $lastMonth->month)
                ->count();

            if ($totalLastMonth > 0) {
                $growth = (($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100;
            } else {
                $growth = 100;
            }

            $growthDirection = $growth >= 0 ? 'up' : 'down';
            $growthAbs = abs($growth);


            $activities = ActivityLogs::latest()
                        ->limit(7)
                        ->get();

             // Hitung session aktif bulan ini
            $currentMonthSessions = DB::table('sessions')
                ->whereMonth('last_activity', Carbon::now()->month)
                ->count();

            // Hitung session aktif bulan lalu
            $lastMonthSessions = DB::table('sessions')
                ->whereMonth('last_activity', Carbon::now()->subMonth()->month)
                ->count();

            // Hitung persentase naik / turun
            if ($lastMonthSessions == 0) {
                $percentage = 100;
            } else {
                $percentage = (($currentMonthSessions - $lastMonthSessions) / $lastMonthSessions) * 100;
            }

            $current = $currentMonthSessions;
            $percentage = round($percentage, 1);
            $isUp = $percentage >= 0;

            return view('dashboard', compact(
                'totalThisMonth',
                'lastMonth',
                'totalLastMonth',
                'growthDirection',
                'growthAbs',
                'result',
                'activities',
                'current',
                'percentage',
                'isUp'
            ));
        } else {
            $summary = $this->getUserImageSummary(
                $user->id,
                $user->kerjasama->client_id,
                now()
            );

            return view('pages.user.dashboard', [
                'uploadDraft' => $summary->uploadDraft,
                'allImages' => $summary->allImages,
                'totalImageCount' => $summary->totalImageCount,
                'percentage' => $percentage ?? 0
            ]);
        }
    }

    public function performancePerMonth()
    {
        if (Auth::user()->role_id == 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = Auth::id();

        $performance = UploadImage::selectRaw("
                MONTH(created_at) as month,
                (
                    COUNT(CASE WHEN img_before IS NOT NULL AND img_before != '' AND img_before != 'none' THEN 1 END) +
                    COUNT(CASE WHEN img_proccess IS NOT NULL AND img_proccess != '' AND img_proccess != 'none' THEN 1 END) +
                    COUNT(CASE WHEN img_final IS NOT NULL AND img_final != '' AND img_final != 'none' THEN 1 END)
                ) as total
            ")
            ->where('user_id', $userId)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $totals = [];

        foreach ($performance as $row) {
            $monthNumber = (int) $row->month;
            $months[] = Carbon::create()->month($monthNumber)->format('F');
            $totals[] = $row->total;
        }

        return response()->json([
            'months' => $months,
            'totals' => $totals,
        ]);
    }

    private function getUserImageSummary($userId, $clientId, $date)
    {
        $uploadDraft = UploadImage::where('user_id', $userId)
            ->where('status', 0)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();

        $allImages = UploadImage::where('clients_id', $clientId)
            ->where('status', 1)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->latest()
            ->get();

        $imageCounts = FixedImage::where('clients_id', $clientId)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();


        return (object)[
            'uploadDraft' => $uploadDraft,
            'allImages' => $allImages,
            'totalImageCount' => $imageCounts
        ];
    }

}
