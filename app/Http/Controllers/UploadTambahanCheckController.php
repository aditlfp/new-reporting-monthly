<?php

namespace App\Http\Controllers;

use App\Services\UploadTambahanService;
use Illuminate\Http\Request;

class UploadTambahanCheckController extends Controller
{
    public function index(Request $request, UploadTambahanService $service)
    {
        try {
            $service->ensureUserCanCheck($request->user());
        } catch (\Throwable $e) {
            abort(403, $e->getMessage());
        }

        return view('pages.user.check_upload_tambahan.index');
    }

    public function summary(Request $request, UploadTambahanService $service)
    {
        try {
            $period = $service->resolvePeriod(
                $request->filled('month') ? (int) $request->input('month') : null,
                $request->filled('year') ? (int) $request->input('year') : null,
            );

            $data = $service->getCheckSummary($request->user(), $period['month'], $period['year']);

            return response()->json([
                'status' => true,
                ...$data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function detail(Request $request, int $userId, UploadTambahanService $service)
    {
        try {
            $period = $service->resolvePeriod(
                $request->filled('month') ? (int) $request->input('month') : null,
                $request->filled('year') ? (int) $request->input('year') : null,
            );

            $data = $service->getCheckDetail($request->user(), $userId, $period['month'], $period['year']);

            return response()->json([
                'status' => true,
                'month' => $period['month'],
                'year' => $period['year'],
                ...$data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }
}

