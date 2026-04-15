<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelChunkUploadRequest;
use App\Http\Requests\FinalizeChunkUploadRequest;
use App\Http\Requests\InitChunkUploadRequest;
use App\Http\Requests\StoreUploadImageDraftRequest;
use App\Http\Requests\StoreUploadImagePdfRequest;
use App\Http\Requests\StoreUploadImageRequest;
use App\Http\Requests\UpdateUploadImageRequest;
use App\Http\Requests\UploadChunkPartRequest;
use App\Models\UploadImage;
use App\Services\UploadImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadImageController extends Controller
{
    public function index(Request $request, UploadImageService $service)
    {
        $data = $service->getIndexData($request);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['images'],
                'draft' => $data['draft'],
            ]);
        }

        return view('upload_images.index', [
            'images' => $data['images'],
        ]);
    }

    public function countData(Request $request, UploadImageService $service)
    {
        return response()->json([
            'status' => true,
            'data' => $service->countDrafts($request->user()->id),
        ]);
    }

    public function store(StoreUploadImageRequest $request, UploadImageService $service)
    {
        try {
            $service->runOpportunisticTempCleanup();

            if (!$request->hasFile('img_before') && !$request->filled('temp_img_before')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Foto Before wajib diisi.',
                ], 422);
            }

            if (!$request->hasFile('img_final') && !$request->filled('temp_img_final')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Foto After wajib diisi.',
                ], 422);
            }

            $upload = $service->store($request);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Upload created successfully',
                    'data' => $upload,
                ], 201);
            }

            return redirect()
                ->route('upload-img-lap.index')
                ->with('success', 'Upload created successfully.');
        } catch (\Throwable $e) {
            Log::error('message: error on UploadImageController', [
                'exception' => $e,
            ]);

            return $request->ajax()
                ? response()->json([
                    'status' => false,
                    'message' => $e->getMessage() ?: 'Failed to created data.',
                    'error' => $e->getMessage(),
                ], 422)
                : back()->with('error', 'Failed to created data.');
        }
    }

    public function draft(StoreUploadImageDraftRequest $request, UploadImageService $service)
    {
        try {
            $service->runOpportunisticTempCleanup();
            $upload = $service->storeDraft($request);

            return $request->ajax()
                ? response()->json([
                    'status' => true,
                    'message' => 'Draft saved successfully',
                    'data' => $upload,
                ], 201)
                : redirect()->route('upload-img-lap.index')->with('success', 'Draft saved successfully.');
        } catch (\Throwable $e) {
            Log::error('message: error on UploadImageController::draft', [
                'exception' => $e,
            ]);

            return $request->ajax()
                ? response()->json([
                    'status' => false,
                    'message' => $e->getMessage() ?: 'Failed to created data.',
                    'error' => $e->getMessage(),
                ], 422)
                : back()->with('error', 'Failed to created data.');
        }
    }

    public function show(Request $request, UploadImage $uploadImage)
    {
        $user = $request->user();

        if ((int) $uploadImage->clients_id !== (int) $user->clients_id || (int) $uploadImage->user_id !== (int) $user->id) {
            $message = 'Unauthorized';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : abort(403, $message);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Get Data By ID',
                'data' => $uploadImage,
            ], 201);
        }

        return view('upload_images.show', compact('uploadImage'));
    }

    public function update(UpdateUploadImageRequest $request, int $id, UploadImageService $service)
    {
        try {
            $service->runOpportunisticTempCleanup();
            $updatedUpload = $service->updateUpload($request, $id);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Upload updated successfully',
                    'data' => $updatedUpload,
                ]);
            }

            return redirect()->route('upload-images.index')->with('success', 'Upload updated successfully.');
        } catch (ModelNotFoundException) {
            return $request->ajax()
                ? response()->json(['message' => 'Unauthorized'], 403)
                : abort(403, 'Unauthorized');
        } catch (\Throwable $e) {
            Log::error('message: error on UploadImageController::update', [
                'exception' => $e,
            ]);

            return $request->ajax()
                ? response()->json([
                    'status' => false,
                    'message' => $e->getMessage() ?: 'Failed to update data.',
                    'error' => $e->getMessage(),
                ], 422)
                : back()->with('error', 'Failed to update data.');
        }
    }

    public function initChunkUpload(InitChunkUploadRequest $request, UploadImageService $service)
    {
        $service->runOpportunisticTempCleanup();
        $uploadId = $service->initChunkUpload($request->user()->id, $request->validated());

        return response()->json([
            'status' => true,
            'upload_id' => $uploadId,
        ]);
    }

    public function uploadChunk(UploadChunkPartRequest $request, UploadImageService $service)
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

    public function finalizeChunkUpload(FinalizeChunkUploadRequest $request, UploadImageService $service)
    {
        $service->runOpportunisticTempCleanup();
        $result = $service->finalizeChunkUpload($request->user()->id, $request->validated()['upload_id']);

        return response()->json([
            'status' => true,
            ...$result,
        ]);
    }

    public function cancelChunkUpload(CancelChunkUploadRequest $request, UploadImageService $service)
    {
        $service->runOpportunisticTempCleanup();
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

    public function destroy(Request $request, UploadImage $uploadImage, UploadImageService $service)
    {
        try {
            $service->deleteUserUpload($request, $uploadImage);
        } catch (ModelNotFoundException) {
            return $request->ajax()
                ? response()->json(['message' => 'Unauthorized'], 403)
                : abort(403, 'Unauthorized');
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Upload deleted successfully']);
        }

        return redirect()->route('upload-img-lap.index')->with('success', 'Upload deleted successfully.');
    }

    public function getPdfData(Request $request, UploadImageService $service)
    {
        return response()->json($service->getPdfData($request));
    }

    public function storePdf(StoreUploadImagePdfRequest $request, UploadImageService $service)
    {
        return response()->json($service->storePdf($request));
    }
}
