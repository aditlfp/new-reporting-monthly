<?php

namespace App\Services\Monitoring;

use App\Repositories\Contracts\MonitoringRepositoryInterface;
use App\Services\Shared\RoleScopeService;
use Illuminate\Http\Request;

class CalendarModalService
{
    public function __construct(
        private readonly MonitoringRepositoryInterface $repository,
        private readonly RoleScopeService $roleScope,
    ) {}

    public function getModalData(Request $request): array
    {
        $user = $request->user();
        $clientId = (int) $request->input('client_id');
        $userIds = $this->roleScope->allowedUserIds($user, (int) $user->kerjasama->client->id);

        $uploads = $this->repository->getUploadsByDateAndClient(
            $userIds,
            $clientId,
            (string) $request->input('date'),
        )->map(function ($item) {
            $item->created_at_formatted = $item->created_at->translatedFormat('d F Y H:i');
            return $item;
        });

        return $uploads->all();
    }
}
