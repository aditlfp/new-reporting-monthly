<?php

namespace App\Http\Controllers;

use App\Services\Monitoring\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function index()
    {
        $user = Auth::user();

        if ($user->role_id == 2) {
            return view('dashboard', $this->service->getAdminDashboardData());
        }

        $summary = $this->service->getUserDashboardData(
            (int) $user->id,
            (int) $user->kerjasama->client_id,
            now(),
        );

        return view('pages.user.dashboard', [
            'uploadDraft' => $summary['uploadDraft'],
            'allImages' => $summary['allImages'],
            'totalImageCount' => $summary['totalImageCount'],
            'percentage' => 0,
        ]);
    }

    public function performancePerMonth()
    {
        if (Auth::user()->role_id == 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->service->getPerformancePerMonth((int) Auth::id()));
    }
}
