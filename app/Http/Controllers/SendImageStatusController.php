<?php

namespace App\Http\Controllers;

use App\Services\Monitoring\SendImageStatusService;
use Exception;
use Illuminate\Http\Request;

class SendImageStatusController extends Controller
{
    public function __construct(
        private readonly SendImageStatusService $service,
    ) {}

    public function index(Request $request)
    {
        return view('pages.admin.send_image_status.index', $this->service->indexData($request->all()));
    }

    public function show($id, $client, $month, $year)
    {
        try {
            return view('pages.admin.send_image_status.show', $this->service->showData(
                (int) $id,
                (int) $client,
                (int) $month,
                (int) $year,
            ));
        } catch (Exception $e) {
            throw new Exception('Error Processing Request: ' . $e->getMessage(), 1);
        }
    }

    public function getDetailFixed($user_id, $month, $year)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get Data Detail Image Has Accept!',
                'data' => $this->service->detailFixed((int) $user_id, $month, $year),
            ]);
        } catch (Exception $e) {
            throw new Exception('Error Processing Request: ' . $e->getMessage(), 1);
        }
    }
}
