<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\FixedImage;
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
        $month = $request->month;
        $clientId = $request->client_id;
        $uploadMin = $request->upload_min ?? 1;
        $uploadMax = $request->upload_max ?? 14;

        $usersAbsensi = DB::connection('dbAbsensi')
            ->table('users')
            ->join('divisis', 'users.devisi_id', '=', 'divisis.id')
            ->join('jabatans', 'divisis.jabatan_id', '=', 'jabatans.id')
            ->select(
                'users.id',
                'users.nama_lengkap',
                'users.email',
                'users.image',
                'divisis.name',
                'jabatans.name_jabatan'
            )
            ->get();

        $absensiUsersIndexed = $usersAbsensi->keyBy('id');

        $uploadsQuery = DB::table('upload_images')
            ->select(
                'user_id',
                'clients_id',
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw("SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_count")
            )
            ->groupBy('user_id', 'clients_id', 'month', 'year')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->orderBy('user_id');

        // Filter
        if ($month) {
            $uploadsQuery->having('month', '=', $month);
        }

        if ($clientId) {
            $uploadsQuery->where('clients_id', $clientId);
        }

        $uploads = $uploadsQuery->paginate(20);


        foreach ($uploads as $upload) {
            $user = $absensiUsersIndexed[$upload->user_id] ?? null;

            $upload->user = $user;
            $upload->divisi = $user->nama_divisi ?? '-';
            $upload->jabatan = $user->name_jabatan ?? '-';

            $upload->has_uploaded_today = $upload->today_count > 0;
        }

        $months = [
            ['value' => 1, 'label' => 'Januari'],
            ['value' => 2, 'label' => 'Februari'],
            ['value' => 3, 'label' => 'Maret'],
            ['value' => 4, 'label' => 'April'],
            ['value' => 5, 'label' => 'Mei'],
            ['value' => 6, 'label' => 'Juni'],
            ['value' => 7, 'label' => 'Juli'],
            ['value' => 8, 'label' => 'Agustus'],
            ['value' => 9, 'label' => 'September'],
            ['value' => 10, 'label' => 'Oktober'],
            ['value' => 11, 'label' => 'November'],
            ['value' => 12, 'label' => 'Desember'],
        ];

        $clients = Clients::get();

        return view('pages.admin.send_image_status.index', [
            'uploads' => $uploads,
            'months' => $months,
            'clients' => $clients
        ]);
    }


    public function show($id, $client, $month, $year)
    {
        try {
            $user = User::with('divisi.jabatan')->findOrFail($id);
            $jabatan = strtolower($user->divisi->jabatan->name_jabatan ?? '');

            $UploadsAll = UploadImage::with('clients', 'user.divisi.jabatan')
                ->where('user_id', $id)
                ->where('clients_id', $client)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->get();

            $jabatan = strtolower(optional(
                $UploadsAll->first()?->user?->divisi?->jabatan
            )->name_jabatan);

            if (!$jabatan) {
                return $UploadsAll;
            }

            if (str_contains($jabatan, 'clean')) {
                // Jika user Cleaning, maka kecualikan Security
                $UploadsAll = $UploadsAll->filter(function ($item) {
                    $jab = strtolower(optional($item->user->divisi->jabatan)->name_jabatan);
                    return !str_contains($jab, 'secu') &&
                           !str_contains($jab, 'scur') &&
                           !str_contains($jab, 'sekur');
                });
            } else {
                $UploadsAll = $UploadsAll->filter(function ($item) {
                    $jab = strtolower(optional($item->user->divisi->jabatan)->name_jabatan);
                    return !str_contains($jab, 'clean');
                });
            }
            $fixed = FixedImage::with('clients', 'user.divisi.jabatan')
                            ->where('clients_id', $client)
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();

            return view('pages.admin.send_image_status.show', compact('UploadsAll', 'fixed'));
        } catch (Exception $e) {
            throw new Exception("Error Processing Request: ". $e->getMessage(), 1);
        }
    }

    public function getDetailFixed($user_id, $month, $year)
    {
        try {
            $fixed = FixedImage::where('user_id', $user_id)
                ->when($month != 'all', function ($q) use ($month) {
                    $q->whereMonth('created_at', $month);
                })
                ->when($year != 'all', function ($q) use ($year) {
                    $q->whereYear('created_at', $year);
                })
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Get Data Detail Image Has Accept!',
                'data' => $fixed
            ]);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request: ". $e->getMessage(), 1);
        }
    }

}
