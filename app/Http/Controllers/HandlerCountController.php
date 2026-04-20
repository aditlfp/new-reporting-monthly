<?php

namespace App\Http\Controllers;

use App\Services\Monitoring\HandlerCountService;
use Exception;
use Illuminate\Http\Request;

class HandlerCountController extends Controller
{
    public function __construct(
        private readonly HandlerCountService $service,
    ) {}

    public function index()
    {
        return view('pages.user.counting.index', $this->service->indexData());
    }

    public function getCountJatim(Request $request)
    {
        $data = $this->service->countJatim($request);

        return response()->json([
            'status' => true,
            'message' => 'Get Counting Verify Image!',
            'data' => [
                'users' => $data['users'],
                'count_today' => $data['count_today'],
            ],
        ], 200);
    }

    public function show($id, $month, $year)
    {
        try {
            if (!$id || !$month || !$year) {
                return response()->json([
                    'status' => false,
                    'message' => 'Filter Missing Not Filled!',
                ], 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'Get Data with params user_id',
                'data' => $this->service->show((int) $id, (int) $month, (int) $year),
            ], 200);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan', 500);
        }
    }
}
