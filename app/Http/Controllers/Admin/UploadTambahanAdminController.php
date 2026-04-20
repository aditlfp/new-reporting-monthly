<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UploadTambahanService;
use Illuminate\Http\Request;

class UploadTambahanAdminController extends Controller
{
    public function index(Request $request, UploadTambahanService $service)
    {
        $filters = [
            'mitra' => $request->filled('mitra') ? (int) $request->input('mitra') : null,
            'month' => $request->filled('month') ? (int) $request->input('month') : null,
            'year' => $request->filled('year') ? (int) $request->input('year') : null,
            'search' => $request->filled('search') ? (string) $request->input('search') : null,
        ];

        $data = $service->getAdminIndexData($filters);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['uploads'],
            ]);
        }

        return view('pages.admin.upload_tambahan.index', [
            'uploads' => $data['uploads'],
            'clients' => $data['clients'],
        ]);
    }

    public function show(int $id, UploadTambahanService $service)
    {
        $data = $service->getAdminDetail($id);

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
}

