<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\Jabatan;
use App\Models\UploadImage;
use App\Models\User;
use App\Services\FixedServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixedImageController extends Controller
{
    public function index()
    {
        $clients = Clients::select('id', 'name')->get();

        return view('pages.user.set_fixed.index', compact('clients'));
    }

    public function create(Request $request)
    {
        try {
            $scope = $this->resolveScope($request);
            $uploadImageIdsQuery = $this->buildUploadImageIdsQuery(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids']
            );

            $client = Clients::query()
                ->select('id', 'name', 'address')
                ->find($scope['client_id']);

            $image = UploadImage::query()
                ->select('id', 'user_id', 'clients_id', 'img_before', 'img_proccess', 'img_final', 'note', 'created_at')
                ->with([
                    'fixedImage:id,upload_image_id,user_id',
                    'fixedImage.user:id,nama_lengkap',
                    'user:id,nama_lengkap',
                ])
                ->whereIn('id', $uploadImageIdsQuery)
                ->latest()
                ->paginate(6);

            $fixed = FixedImage::query()
                ->select('upload_image_id', 'user_id')
                ->where('clients_id', $scope['client_id'])
                ->whereBetween('created_at', [$scope['start_at'], $scope['end_at']])
                ->whereIn('user_id', $scope['allowed_user_ids'])
                ->get();

            $counts = $this->buildCounts(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids']
            );


            return response()->json([
                'status' => true,
                'message' => 'Get All Required Data',
                'data' => [
                    'client' => $client,
                    'image' => $image,
                    'fixed' => $fixed,
                    'counts' => $counts,
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request" . $e->getMessage(), 1);
        }
    }

    public function store(Request $request, FixedServices $services)
    {
        try {
            $result = $services->setImage($request->all());

            if (! $result['success']) {
                return response()->json([
                    'success' => false,
                    'limit' => $result['limit'] ?? false,
                    'message' => $result['message'],
                ], 422);
            }

            $scope = $this->resolveScope($request);
            $counts = $this->buildCounts(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids']
            );

            return response()->json([
                'success' => true,
                'limit' => false,
                'message' => $result['message'],
                'data' => $result['data'],
                'counts' => $counts,
                'fixed_state' => [
                    'upload_image_id' => (int) $result['data']->upload_image_id,
                    'is_fixed' => true,
                    'verified_by' => auth()->user()?->nama_lengkap,
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request" . $e->getMessage(), 1);
        }
    }

    public function destroy($id, Request $request, FixedServices $services)
    {
        try {
            $val = $services->removeSelection((int) $id);
            if (! $val['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $val['message'],
                ], 404);
            }

            $scope = $this->resolveScope($request);
            $counts = $this->buildCounts(
                $scope['client_id'],
                $scope['start_at'],
                $scope['end_at'],
                $scope['allowed_user_ids']
            );

            return response()->json([
                'status' => true,
                'message' => $val['message'],
                'counts' => $counts,
                'fixed_state' => [
                    'upload_image_id' => (int) $id,
                    'is_fixed' => false,
                    'verified_by' => null,
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request" . $e->getMessage(), 1);
        }
    }

    public function getCountFixed(Request $request) // admin checkCount
    {
        $scope = $this->resolveScope($request);
        $counts = $this->buildCounts(
            $scope['client_id'],
            $scope['start_at'],
            $scope['end_at'],
            $scope['allowed_user_ids']
        );

        return response()->json([
            'status' => true,
            'message' => 'Get Counting FixedImage',
            'data' => [
                'count' => $counts['total_fixed'],
                'count_today' => $counts['count_today'],
            ],
        ], 200);
    }

    private function resolveScope(Request $request): array
    {
        $user = auth()->user();
        $requestedClientId = $request->input('client_id');
        $fallbackClientId = $user?->kerjasama?->client_id;
        $clientId = (int) ($requestedClientId ?: $fallbackClientId);

        $month = (int) ($request->input('month') ?: now()->month);
        $year = (int) ($request->input('year') ?: now()->year);
        $startAt = Carbon::create($year, $month, 1)->startOfMonth();
        $endAt = (clone $startAt)->endOfMonth();

        return [
            'client_id' => $clientId,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'allowed_user_ids' => $this->resolveAllowedUserIds($user),
        ];
    }

    private function resolveAllowedUserIds(User $user): array
    {
        $typeJabatanUser = Str::upper((string) ($user->jabatan?->name_jabatan ?? ''));
        $typeJabatanUser = str_replace('pusat', 'PUSAT', $typeJabatanUser);
        $isSecurity = Str::contains($typeJabatanUser, 'SUPERVISOR PUSAT SECURITY');

        if (! $isSecurity && $typeJabatanUser === 'DANRU SECURITY') {
            $type = ['SECURITY'];
        } else {
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
                    'TEKNISI',
                ];
        }

        $jabatanIds = Jabatan::query()
            ->whereIn(DB::raw('UPPER(type_jabatan)'), $type)
            ->pluck('id');

        return User::query()
            ->whereIn('jabatan_id', $jabatanIds)
            ->pluck('id')
            ->all();
    }

    private function buildUploadImageIdsQuery(int $clientId, Carbon $startAt, Carbon $endAt, array $allowedUserIds): Builder
    {
        return UploadImage::query()
            ->select('id')
            ->where('clients_id', $clientId)
            ->where('status', 1)
            ->whereBetween('created_at', [$startAt, $endAt])
            ->whereIn('user_id', $allowedUserIds);
    }

    private function buildCounts(int $clientId, Carbon $startAt, Carbon $endAt, array $allowedUserIds): array
    {
        $uploadImageIds = $this->buildUploadImageIdsQuery($clientId, $startAt, $endAt, $allowedUserIds);

        $baseCountQuery = FixedImage::query()
            ->where('clients_id', $clientId)
            ->whereBetween('created_at', [$startAt, $endAt])
            ->whereIn('upload_image_id', $uploadImageIds);

        $totalFixed = (clone $baseCountQuery)->count();
        $countToday = (clone $baseCountQuery)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return [
            'total_fixed' => $totalFixed,
            'count_today' => $countToday,
        ];
    }
}
