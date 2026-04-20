<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadTambahanRequest;
use App\Http\Requests\UploadTambahanChunkCancelRequest;
use App\Http\Requests\UploadTambahanChunkFinalizeRequest;
use App\Http\Requests\UploadTambahanChunkInitRequest;
use App\Http\Requests\UploadTambahanChunkPartRequest;
use App\Services\UploadTambahanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadTambahanController extends Controller
{
    public function index(Request $request, UploadTambahanService $service)
    {
        try {
            $data = $service->getUserIndexData($request->user());
            return view('pages.user.upload_tambahan.index', $data);
        } catch (\Throwable $e) {
            abort(403, $e->getMessage());
        }
    }

    public function show(Request $request, UploadTambahanService $service)
    {
        try {
            $data = $service->getUserIndexData($request->user());
            return view('pages.user.upload_tambahan.show', $data);
        } catch (\Throwable $e) {
            abort(403, $e->getMessage());
        }
    }
    public function detail(Request $request, $id, UploadTambahanService $service)
    {
        $data = $service->getShowDetail($request->user(), $id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            ...$data,
        ]);
    }
    public function store(StoreUploadTambahanRequest $request, UploadTambahanService $service)
    {
        try {
            $upload = $service->createUpload($request->user(), $request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Upload tambahan berhasil disimpan.',
                'data' => $upload,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to store upload tambahan', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ?: 'Gagal menyimpan upload tambahan.',
            ], 422);
        }
    }

    public function initChunkUpload(UploadTambahanChunkInitRequest $request, UploadTambahanService $service)
    {
        $uploadId = $service->initChunkUpload($request->user()->id, $request->validated());

        return response()->json([
            'status' => true,
            'upload_id' => $uploadId,
        ]);
    }

    public function uploadChunk(UploadTambahanChunkPartRequest $request, UploadTambahanService $service)
    {
        $validated = $request->validated();
        $meta = $service->storeChunkPart($request->user()->id, $validated, $request);

        return response()->json([
            'status' => true,
            'upload_id' => $validated['upload_id'],
            'chunk_index' => (int) $validated['chunk_index'],
            'field' => $meta['field'] ?? null,
        ]);
    }

    public function finalizeChunkUpload(UploadTambahanChunkFinalizeRequest $request, UploadTambahanService $service)
    {
        $result = $service->finalizeChunkUpload($request->user()->id, $request->validated()['upload_id']);

        return response()->json([
            'status' => true,
            ...$result,
        ]);
    }

    public function cancelChunkUpload(UploadTambahanChunkCancelRequest $request, UploadTambahanService $service)
    {
        $validated = $request->validated();

        $service->cancelChunkUpload(
            $request->user()->id,
            $validated['temp_token'] ?? null,
            $validated['upload_id'] ?? null,
        );

        return response()->json([
            'status' => true,
            'message' => 'Temporary upload canceled.',
        ]);
    }
}
