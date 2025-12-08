<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\Clients;
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

        $data = UploadImage::with([
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

                    $quota = 14;

                    $percentage = $quota > 0
                        ? round(($totalUpload / $quota) * 100, 1)
                        : 0;

                    $result[] = [
                        'client' => Clients::find($clientId)->name ?? 'Unknown',
                        'jabatan' => $jabatan,
                        'uploads' => $totalUpload,
                        'quota' => $quota,
                        'percentage' => $percentage,
                    ];
                }
            }

        if (Auth::user()->role_id == 2) {

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
            $uploadDraft = UploadImage::where('user_id', Auth::user()->id)
                ->where('status', 0)
                ->latest()
                ->first();
            $allImages = UploadImage::where('clients_id', Auth::user()->kerjasama->client_id)
                ->where('status', 1)
                ->latest()
                ->get();
            $imageCount = UploadImage::where('clients_id', Auth::user()->kerjasama->client_id)
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

            return view('pages.user.dashboard', compact('uploadDraft', 'totalImageCount', 'allImages', 'percentage'));
        }
    }
}
