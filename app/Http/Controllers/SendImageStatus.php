<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\UploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SendImageStatus extends Controller
{
    // *
    // * params for function index(Request $request)
    // * @month date->month only
    // * @client_id int
    // * @status int
    // * @upload_min int
    // * @upload_max int
    // *
    public function index(Request $request)
    {
        $filters = $request->only(['month', 'client_id', 'status', 'upload_min', 'upload_max']);

        $clientIds = collect();
        if (($filters['upload_min'] ?? null) || ($filters['upload_max'] ?? null)) {
            $query = UploadImage::select('clients_id')
                ->groupBy('clients_id');

            if (!empty($filters['upload_min'])) {
                $query->havingRaw('COUNT(*) >= ?', [$filters['upload_min']]);
            }
            if (!empty($filters['upload_max'])) {
                $query->havingRaw('COUNT(*) <= ?', [$filters['upload_max']]);
            }

            $clientIds = $query->pluck('clients_id');
        }

        $uploads = UploadImage::with('clients')
            ->when($filters['month'] ?? null, fn($q, $month) => $q->whereMonth('created_at', $month))
            ->when($filters['client_id'] ?? null, fn($q, $id) => $q->where('clients_id', $id))
            ->when($clientIds->isNotEmpty(), fn($q) => $q->whereIn('clients_id', $clientIds))
            ->latest()
            ->paginate(10);

        $unique = $uploads->getCollection()->unique('user_id');
        $uploads->setCollection($unique);

        $dataCount = DB::table('upload_images')
            ->select('clients_id', DB::raw('COUNT(*) as total'))
            ->groupBy('clients_id')
            ->get();

        $clients = Clients::all();

        $userMonthlyCount = 0;
        $totalUploads = 0;

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                'value' => $i,
                'label' => Carbon::create()->month($i)->locale('id')->isoFormat('MMMM')
            ];
        }

        return view('pages.admin.send_image_status.index', compact(
            'uploads',
            'clients',
            'userMonthlyCount',
            'totalUploads',
            'months',
            'dataCount'
        ));
    }

}
