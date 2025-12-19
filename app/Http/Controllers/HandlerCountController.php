<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\Kerjasama;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HandlerCountController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();;
    }

    public function index()
    {
        return view('pages.user.counting.index');
    }

    public function getCountJatim(Request $request)
    {
        // dd($request->all());
        $province = strtolower($this->user->kerjasama->client->province);

        $isSecurity = Str::contains(strtolower($this->user->jabatan->name_jabatan), 'supervisor pusat security')
            ? 'DANRU SECURITY'
            : 'LEADER CS';

        $clients = Clients::whereRaw('LOWER(province) = ?', [$province])->get();

        $usersId = [];
        $users = User::whereHas('jabatan', function ($query) use ($isSecurity) {
            $query->whereRaw('LOWER(name_jabatan) LIKE ?', ['%'.$isSecurity.'%']);
        })->get();

        foreach ($users as $key => $value) {
            $usersId[] = $value->id;
        }

        $usersId[] = $this->user->id;

        $userSpesified = User::with(['jabatan', 'kerjasama.client'])
                                ->whereIn('id', $usersId)
                                ->withCount(['fixedImages as total_per_month' => function ($q) use ($request) {
                                    $q->whereMonth('created_at', $request->month ?? now()->month)
                                      ->whereYear('created_at',  $request->year ?? now()->year);
                                }])
                                ->get();
        $countToday = FixedImage::select(
                        'user_id',
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as total_upload')
                    )
                    ->whereIn('user_id', $usersId)
                    ->whereDate('created_at', now())
                    ->groupBy('user_id', DB::raw('DATE(created_at)'))
                    ->get();


        return response()->json([
            'status' => true,
            'message' => 'Get Counting Verify Image!',
            'data' => [
                'users' => $userSpesified,
                'count_today' => $countToday
            ]

        ], 200);
    }

    public function show($id, $month, $year)
    {
        try {
            if(!$id || !$month || !$year)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Filter Missing Not Filled!',
                ], 422);
            }


            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end   = Carbon::create($year, $month, 1)->endOfMonth();
            $user = User::select('id', 'nama_lengkap', 'kerjasama_id')
                            ->where('id', $id)
                            ->first();
            $mitra = Kerjasama::select('client_id')->with('client:id,name')->where('client_id', $user->kerjasama->client_id)->first();
            $data = FixedImage::with(['user', 'uploadImage.user:id,nama_lengkap'])
                    ->where('user_id', $id)
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Get Data with params user_id',
                    'data' => [
                        'fixed' => $data,
                        'user' => $user,
                        'client' => $mitra,
                    ]
                ],200);
            } catch (Exception $e) {
                throw new \Exception('Terjadi kesalahan', 500);
            }
    }
}
