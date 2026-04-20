<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminMassDeleteUploadImageRequest;
use App\Http\Requests\AdminUpdateUploadImageRequest;
use App\Models\UploadImage;
use App\Services\UploadImageService;
use Illuminate\Http\Request;

class DataFotoController extends Controller
{
    public function index(Request $request, UploadImageService $service)
    {
        $data = $service->getAdminIndexData($request);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['images'],
                'users' => $data['users'],
            ]);
        }

        return view('pages.admin.fotoProgres.index', [
            'images' => $data['images'],
            'client' => $data['client'],
        ]);
    }

    public function update(AdminUpdateUploadImageRequest $request, UploadImage $uploadImage, UploadImageService $service)
    {
        $uploadImage = $service->updateAdminUpload($uploadImage, $request);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload updated successfully',
                'data' => $uploadImage,
            ]);
        }

        return redirect()->route('admin.upload.index')->with('success', 'Upload updated successfully.');
    }

    public function show(UploadImage $uploadImage)
    {
        return response()->json([
            'status' => true,
            'message' => 'Get upload image data successfully',
            'data' => $uploadImage->load(['clients', 'user.kerjasama']),
        ]);
    }

    public function destroy(Request $request, UploadImage $uploadImage, UploadImageService $service)
    {
        $service->deleteAdminUpload($uploadImage);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload deleted successfully',
            ]);
        }

        return redirect()->route('admin.upload.index')->with('success', 'Upload deleted successfully.');
    }

    public function massDelete(AdminMassDeleteUploadImageRequest $request, UploadImageService $service)
    {
        $service->massDeleteAdminUploads($request->validated()['ids']);

        return response()->json([
            'status' => true,
            'message' => 'Selected uploads deleted successfully',
        ]);
    }

    public function getUsers(Request $request, UploadImageService $service)
    {
        return response()->json([
            'status' => true,
            'data' => $service->getUsersByMitra((int) $request->mitra_id),
        ]);
    }
}
