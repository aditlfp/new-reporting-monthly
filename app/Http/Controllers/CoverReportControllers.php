<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoverRequest;
use App\Http\Requests\CoverStorePdfRequest;
use App\Services\Media\CoverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoverReportControllers extends Controller
{
    public function __construct(
        private readonly CoverService $service,
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->indexData();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['covers'],
                'client' => $data['client'],
            ]);
        }

        return view('pages.admin.covers.index', [
            'covers' => $data['covers'],
            'client' => $data['client'],
        ]);
    }

    public function show(Request $request, $id)
    {
        try {
            $cover = $this->service->showById((int) $id);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Get cover by ID.',
                    'data' => $cover,
                ], 200);
            }

            return view('pages.admin.covers.show', compact('cover'));
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CoverRequest $request)
    {
        try {
            $cover = $this->service->store(
                $request->validated(),
                $request->file('img_src_1'),
                $request->file('img_src_2'),
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $cover->id,
                    'jenis_rekap' => $cover->jenis_rekap,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->getErrorResponse($e, 'Failed to create cover.');
        }
    }

    public function update(CoverRequest $request, $id)
    {
        try {
            $cover = $this->service->update(
                (int) $id,
                $request->validated(),
                $request->file('img_src_1'),
                (bool) $request->img1_changed,
                $request->file('img_src_2'),
                (bool) $request->img2_changed,
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $cover->id,
                    'client_name' => ucwords(strtolower((string) $cover->client->name)),
                    'jenis_rekap' => $cover->jenis_rekap,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->getErrorResponse($e, 'Failed to update cover.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->service->destroy((int) $id);

            return response()->json([
                'success' => true,
                'message' => 'Cover deleted successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cover',
            ], 500);
        }
    }

    public function store_pdf(CoverStorePdfRequest $request)
    {
        $url = $this->service->mergeAndStorePdf($request->file('pdf'), (int) $request->validated()['srt_id']);

        return response()->json([
            'success' => true,
            'message' => 'PDF successfully to merge and Saved Into Server!',
            'url' => $url,
        ]);
    }

    private function getErrorResponse(\Throwable $e, string $message)
    {
        Log::error($message . ': ' . $e->getMessage());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message . ': ' . $e->getMessage(),
            ], 500);
        }

        return back()->withInput()->withErrors(['error' => $message . ': ' . $e->getMessage()]);
    }
}
