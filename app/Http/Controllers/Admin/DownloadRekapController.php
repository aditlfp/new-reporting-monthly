<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDownloadRekapGenerateRequest;
use App\Services\Media\CoverService;
use Illuminate\Http\Request;

class DownloadRekapController extends Controller
{
    public function __construct(
        private readonly CoverService $service,
    ) {}

    public function index(Request $request)
    {
        $month = $request->filled('month') ? (int) $request->input('month') : (int) now()->month;
        $year = $request->filled('year') ? (int) $request->input('year') : (int) now()->year;
        $clientId = $request->filled('client') ? (int) $request->input('client') : null;

        $data = $this->service->downloadRekapIndexData($clientId, $month, $year);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['covers'],
            ]);
        }

        return view('pages.admin.download_rekap.index', $data);
    }

    public function generate(AdminDownloadRekapGenerateRequest $request)
    {
        try {
            $validated = $request->validated();
            $url = $this->service->downloadRekap(
                (int) $validated['cover_id'],
                (int) $validated['month'],
                (int) $validated['year'],
                $request->file('pdf'),
            );

            return response()->json([
                'success' => true,
                'message' => 'Download rekap berhasil dibuat.',
                'url' => $url,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat download rekap.',
            ], 500);
        }
    }
}
