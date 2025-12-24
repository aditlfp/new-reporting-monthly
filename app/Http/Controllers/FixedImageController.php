<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\Jabatan;
use App\Models\UploadImage;
use App\Models\User;
use App\Services\FixedServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixedImageController extends Controller
{

    public function index()
    {
        $clients = Clients::select('id', 'name')->get();
        return view('pages.user.set_fixed.index', compact('clients'));        
    }

    public function create(Request $request)
    {
        $clientId = $request->client_id;    
        $month = $request->month;
        $year = $request->year;
        try {
            $user = auth()->user();
            $typeJabatanUser = Str::upper($user->jabatan->name_jabatan);

            // normalisasi typo
            $typeJabatanUser = str_replace('pusat', 'PUSAT', $typeJabatanUser);

            $isSecurity = Str::contains($typeJabatanUser, 'SUPERVISOR PUSAT SECURITY');

            if(!$isSecurity && $typeJabatanUser == 'DANRU SECURITY')
            {
                $type = ['SECURITY'];
            }else{
                $type = $isSecurity
                    ? ['SECURITY', 'SUPERVISOR PUSAT SECURITY']
                    : [
                        'CLEANING SERVICE',
                        'FRONT OFFICE',
                        'LEADER',
                        'FO',
                        'KASIR',
                        'KARYAWAN',
                        'TAMAN',
                        'TEKNISI'
                    ];    
            }

            $jabId = Jabatan::whereIn(
                    DB::raw('UPPER(type_jabatan)'),
                    $type
                )
                ->pluck('id')
                ->toArray();
            $userIds = User::select('id')->whereIn('jabatan_id', $jabId)->get();

            $client = Clients::where('id', $clientId ?? $user->kerjasama->client_id)->first();
            $image = UploadImage::with(['fixedImage.user', 'user'])
                        ->where('clients_id', $clientId ??  $client->id)
                        ->whereMonth('created_at', $month ??  now()->month)
                        ->whereYear('created_at', $year ??  now()->year)
                        ->whereIn('user_id', $userIds)
                        ->where("status", 1)
                        ->latest()
                        ->paginate(6);
            // dd($image);
            $fixed = FixedImage::where('clients_id', $clientId ??  $user->kerjasama->client_id)
                                ->whereMonth('created_at', $month ??  now()->month)
                                ->whereYear('created_at', $year ??  now()->year)
                                ->whereIn('user_id', $userIds)
                                ->get();


            return response()->json([
                'status' => true,
                'message' => 'Get All Required Data',
                'data' => [
                    'client' => $client,
                    'image' => $image,
                    'fixed' => $fixed
                ],
            ], 200);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request". $e->getMessage(), 1);
        }
    }

    public function store(Request $request)
    {
        try {
            $services = new FixedServices();
            $val = $services->setImage($request->all());
            if($val['success']){
                return response()->json([
                    'status' => true,
                    'message' => 'Data Has Been created successfully',
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $val['message'],
                ], 300);
            }
            
        } catch (Exception $e) {
            throw new Exception("Error Processing Request". $e->getMessage(), 1);
        }
    }

    public function destroy($id)
    {
        try {
            $services = new FixedServices();
            $val = $services->removeSelection($id);
            if($val['status']){
                return response()->json($val, 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $val['message'],
                ], 300);
            }
            
        } catch (Exception $e) {
            throw new Exception("Error Processing Request". $e->getMessage(), 1);
        }
    }

    public function getCountFixed(Request $request) // admin checkCount
    {
        $clientId = $request->client_id;
        $month = $request->month;
        $year = $request->year;
        $count = FixedImage::where('clients_id', $clientId ?? auth()->user()->kerjasama->client_id)
                    ->whereMonth('created_at', $month ?? now()->month)
                    ->whereYear('created_at', $year ?? now()->year)
                    ->count();
        $countToday = FixedImage::where('clients_id', $clientId ?? auth()->user()->kerjasama->client_id)
                    ->whereDate('created_at', now()->toDateString())
                    ->count();
        return response()->json([
            'status' => true,
            'message' => 'Get Counting FixedImage',
            'data' => [
                'count' => $count,
                'count_today' => $countToday
            ],
        ], 200);
    }
}
