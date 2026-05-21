<?php

namespace App\Services;

use App\Models\Finding;
use Illuminate\Http\Request;

class FindingService
{
    private const DAILY_QUOTA_PER_RUANGAN = 6;

    public function getIndexData(): array
    {
        return [
            'findings' => Finding::with('user')->latest()->get(),
            'remainingQuota' => $this->calculateRemainingQuotaForRequest(),
        ];
    }

    private function calculateRemainingQuotaForRequest(): int|string
    {
        $requestedRuangan = request('n') ?? request('ruangan');

        if (!is_string($requestedRuangan) || trim($requestedRuangan) === '') {
            return 'N/A';
        }

        return $this->calculateRemainingQuota($requestedRuangan);
    }

    private function calculateRemainingQuotaByRuangan(): array
    {
        $today = now()->toDateString();

        $usageByRuangan = Finding::selectRaw('ruangan, count(*) as used')
            ->whereDate('created_at', '=', $today)
            ->groupBy('ruangan')
            ->pluck('used', 'ruangan')
            ->toArray();

        return array_map(static fn ($used) => (int) max(self::DAILY_QUOTA_PER_RUANGAN - (int) $used, 0), $usageByRuangan);
    }

    private function calculateRemainingQuota(string $ruangan): int
    {
        $usedQuota = Finding::where('ruangan', $ruangan)
            ->whereDate('created_at', '=', now()->toDateString())
            ->count();

        return max(self::DAILY_QUOTA_PER_RUANGAN - $usedQuota, 0);
    }

    public function store(Request $request): Finding
    {
        $data = $request->validate([
            'user_id' => 'required',
            'ruangan' => 'required|string',
            'note' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        if ($this->calculateRemainingQuota($data['ruangan']) <= 0) {
            abort(422, 'Kuota harian untuk ruangan ini sudah penuh.');
        }

        return Finding::create([
            'user_id' => $data['user_id'],
            'ruangan' => $data['ruangan'],
            'note' => $data['note'],
            'image_path' => $request->file('image')->store('findings', 'public'),
        ]);
    }
}
