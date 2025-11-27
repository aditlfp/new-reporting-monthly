<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\UploadImage;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendImageStatusController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['month', 'client_id', 'status', 'upload_min', 'upload_max']);

        // FILTER CLIENT BY MIN / MAX UPLOAD
        $clientIds = collect();
        $clientUserIds = [];
        if (($filters['upload_min'] ?? null) || ($filters['upload_max'] ?? null)) {
            $query = UploadImage::select(
                        'user_id',
                        'clients_id',
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('user_id', 'clients_id', 'month', 'year');

                if (!empty($filters['upload_min'])) {
                    $query->havingRaw('COUNT(*) >= ?', [$filters['upload_min']]);
                }
                if (!empty($filters['upload_max'])) {
                    $query->havingRaw('COUNT(*) <= ?', [$filters['upload_max']]);
                }

                // yang kita simpan pasangan user+client+month+year
                $clientUserIds = $query->get()->map(function($row){
                    return $row->user_id.'-'.$row->clients_id.'-'.$row->month.'-'.$row->year;
                })->toArray();
        }

        // MAIN QUERY (GROUP PER USER + CLIENT + MONTH + YEAR)
        $uploads = UploadImage::with('clients', 'user.divisi.jabatan')
            ->select(
                'user_id',
                'clients_id',
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->searchFilters($filters)
            ->groupBy('user_id', 'clients_id', 'month', 'year')
            ->when(!empty($clientUserIds), function($q) use ($clientUserIds) {
                $ids = implode("','", $clientUserIds);
                $q->havingRaw("CONCAT(user_id, '-', clients_id, '-', month, '-', year) IN ('$ids')");
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        $dataCount = UploadImage::searchFilters($filters)
            ->select(
                'clients_id',
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_count')
            )
            ->groupBy('clients_id', 'month', 'year')
            ->get()
            ->keyBy(fn($row) => $row->clients_id.'-'.$row->month.'-'.$row->year);

        $uploads->getCollection()->transform(function ($item) {

            // Ambil jabatan user ini
        $jabatanId = $item->user->divisi->jabatan->id ?? null;

            // Ambil semua upload milik client + month + year
        $filtered = UploadImage::where('clients_id', $item->clients_id)
                ->whereMonth('created_at', $item->month)
                ->whereYear('created_at', $item->year)
                ->get()
                ->filter(function ($u) use ($jabatanId) {
                    return optional($u->user->divisi->jabatan)->id == $jabatanId;
                });

            // Simpan total count baru
            $item->total_count = $filtered->count();

            return $item;
        });

        $clients = Clients::all();

        $userMonthlyCount = 0;
        $totalUploads = 0;

        // Dropdown Month
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

    public function show($id)
    {
        try {
            // 1) REKAP UPLOAD (fast & clean)
            $uploads = UploadImage::select(
                    'user_id',
                    'clients_id',
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('user_id', $id)
                ->groupBy('user_id', 'clients_id', 'month', 'year')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();


            // ambil semua clients_id unik hasil rekap
            $clientIds = $uploads->pluck('clients_id')->unique();

            // 2) LOAD USER + CLIENTS SEKALI SAJA (no N+1)
            $user = User::with('divisi.jabatan')->find($id);

            $clients = Clients::whereIn('id', $clientIds)->get();


            // OPTIONAL: gabungkan clients ke hasil
            $uploads->each(function ($item) use ($clients, $user) {
                $item->setRelation('user', $user);
                $item->setRelation('clients', $clients->firstWhere('id', $item->clients_id));
            });

            $UploadsAll = UploadImage::with('clients', 'user.divisi.jabatan')
                            ->where('user_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();

            return view('pages.admin.send_image_status.show', compact('UploadsAll'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
