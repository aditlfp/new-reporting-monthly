<?php

namespace App\Services\Monitoring;

use App\Repositories\Contracts\MonitoringRepositoryInterface;
use App\Services\Shared\PeriodService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HandlerCountService
{
    public function __construct(
        private readonly MonitoringRepositoryInterface $repository,
        private readonly PeriodService $periodService,
    ) {}

    public function indexData(): array
    {
        return [
            'clients' => $this->repository->getClientsLite(),
        ];
    }

    public function countJatim(Request $request): array
    {
        $user = $request->user();
        $province = strtolower((string) $user->kerjasama->client->province);

        $isSecurity = str_contains(strtolower((string) $user->jabatan->name_jabatan), 'supervisor pusat security')
            ? 'danru security'
            : 'leader cs';

        $usersId = $this->repository->getUsersByJabatanNameLike($isSecurity)->pluck('id')->map(fn ($id) => (int) $id)->all();
        $usersId[] = (int) $user->id;

        $month = (int) ($request->month ?? now()->month);
        $year = (int) ($request->year ?? now()->year);

        return [
            'clients' => $this->repository->getClientsByProvince($province),
            'users' => $this->repository->getUsersWithCountByIds(
                $usersId,
                $request->filled('client') ? (int) $request->input('client') : null,
                $month,
                $year,
            ),
            'count_today' => $this->repository->getTodayCountByUsers($usersId),
        ];
    }

    public function show(int $id, int $month, int $year): array
    {
        $period = $this->periodService->monthRange($month, $year);
        $user = $this->repository->getUserBasic($id);

        return [
            'fixed' => $this->repository->getFixedByUserBetween($id, $period['start_at'], $period['end_at']),
            'user' => $user,
            'client' => $this->repository->getMitraByClientId((int) $user->kerjasama->client_id),
        ];
    }
}
