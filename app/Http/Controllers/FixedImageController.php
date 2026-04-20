<?php

namespace App\Http\Controllers;

use App\Services\Monitoring\FixedImageService;
use Exception;
use Illuminate\Http\Request;

class FixedImageController extends Controller
{
    public function __construct(
        private readonly FixedImageService $service,
    ) {}

    public function index()
    {
        return view('pages.user.set_fixed.index', $this->service->indexData());
    }

    public function create(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get All Required Data',
                'data' => $this->service->createData($request),
            ], 200);
        } catch (Exception $e) {
            throw new Exception('Error Processing Request' . $e->getMessage(), 1);
        }
    }

    public function store(Request $request)
    {
        try {
            $result = $this->service->setImage($request->all(), $request);

            if (! $result['success']) {
                return response()->json([
                    'success' => false,
                    'limit' => $result['limit'] ?? false,
                    'message' => $result['message'],
                ], 422);
            }

            return response()->json([
                'success' => true,
                'limit' => false,
                'message' => $result['message'],
                'data' => $result['data'],
                'counts' => $result['counts'],
                'fixed_state' => [
                    'upload_image_id' => (int) $result['data']->upload_image_id,
                    'is_fixed' => true,
                    'verified_by' => auth()->user()?->nama_lengkap,
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception('Error Processing Request' . $e->getMessage(), 1);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $val = $this->service->removeSelection((int) $id, $request);

            if (! $val['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $val['message'],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => $val['message'],
                'counts' => $val['counts'],
                'fixed_state' => [
                    'upload_image_id' => (int) $id,
                    'is_fixed' => false,
                    'verified_by' => null,
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception('Error Processing Request' . $e->getMessage(), 1);
        }
    }

    public function getCountFixed(Request $request)
    {
        $counts = $this->service->countFixed($request);

        return response()->json([
            'status' => true,
            'message' => 'Get Counting FixedImage',
            'data' => $counts,
        ], 200);
    }
}
