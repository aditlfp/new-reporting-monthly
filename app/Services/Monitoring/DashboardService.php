<?php

namespace App\Services\Monitoring;

use App\Repositories\Contracts\MonitoringRepositoryInterface;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(
        private readonly MonitoringRepositoryInterface $repository,
    ) {}

    public function getAdminDashboardData(): array
    {
        $now = now();
        $data = $this->repository->getFixedGroupedSummary($now->month, $now->year);

        $summary = [];

        foreach ($data as $row) {
            $client = $row->clients_id;
            $jabatan = $row->user->divisi->jabatan->name_jabatan ?? 'Unknown';

            if (!isset($summary[$client][$jabatan])) {
                $summary[$client][$jabatan] = 0;
            }

            $summary[$client][$jabatan] += $row->total;
        }

        $result = [];

        foreach ($summary as $clientId => $jabList) {
            foreach ($jabList as $jabatan => $totalUpload) {
                $quota = 11;
                $percentage = $quota > 0 ? round(($totalUpload / $quota) * 100, 1) : 0;

                $result[] = [
                    'client' => $this->repository->getClientNameById((int) $clientId) ?? 'Unknown',
                    'jabatan' => $jabatan,
                    'uploads' => $totalUpload,
                    'quota' => $quota,
                    'percentage' => $percentage > 100 ? 100 : $percentage,
                ];
            }
        }

        $totalThisMonth = $this->repository->countUploadsForMonth($now->year, $now->month);
        $lastMonth = $now->copy()->subMonth();
        $totalLastMonth = $this->repository->countUploadsForMonth($lastMonth->year, $lastMonth->month);

        $growth = $totalLastMonth > 0
            ? (($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100
            : 100;

        $currentMonthSessions = $this->repository->countSessionsForMonth(Carbon::now()->month);
        $lastMonthSessions = $this->repository->countSessionsForMonth(Carbon::now()->subMonth()->month);

        $percentage = $lastMonthSessions == 0
            ? 100
            : (($currentMonthSessions - $lastMonthSessions) / $lastMonthSessions) * 100;

        return [
            'totalThisMonth' => $totalThisMonth,
            'lastMonth' => $lastMonth,
            'totalLastMonth' => $totalLastMonth,
            'growthDirection' => $growth >= 0 ? 'up' : 'down',
            'growthAbs' => abs($growth),
            'result' => $result,
            'activities' => $this->repository->latestActivities(),
            'current' => $currentMonthSessions,
            'percentage' => round($percentage, 1),
            'isUp' => $percentage >= 0,
        ];
    }

    public function getUserDashboardData(int $userId, int $clientId, $date): array
    {
        return [
            'totalImageCount' => $this->repository->countFixedByClientMonth($clientId, $date->month, $date->year),
            'chart' => $this->getPerformancePerMonth($userId),
        ];
    }

    public function getPerformancePerMonth(int $userId): array
    {
        $currentYear = now()->year;
        $performance = $this->repository->getUserPerformanceByMonth($userId, $currentYear);

        $monthlyTotals = array_fill(1, 12, 0);
        $monthLabels = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        foreach ($performance as $row) {
            $monthNumber = (int) $row->month;
            if ($monthNumber >= 1 && $monthNumber <= 12) {
                $monthlyTotals[$monthNumber] = (int) $row->total;
            }
        }

        return [
            'months' => array_values($monthLabels),
            'totals' => array_values($monthlyTotals),
            'year' => $currentYear,
        ];
    }
}
