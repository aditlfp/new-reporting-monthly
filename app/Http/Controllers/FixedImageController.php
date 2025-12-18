<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\UploadImage;
use App\Services\FixedServices;
use Exception;
use Illuminate\Http\Request;

class FixedImageController extends Controller
{

    public function index()
    {
        return view('pages.user.set_fixed.index');        
    }

    public function create()
    {
        try {
            $user = auth()->user();

            $client = Clients::where('id', $user->kerjasama->client_id)->first();
            $image = UploadImage::with(['fixedImage', 'user'])
                        ->where('clients_id', $client->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where("status", 1)
                        ->get();
            $fixed = FixedImage::where('clients_id', $user->kerjasama->client_id)
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
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

    public function getCountFixed() // admin checkCount
    {
        $count = FixedImage::where('clients_id', auth()->user()->kerjasama->client_id)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
        $countToday = FixedImage::where('clients_id', auth()->user()->kerjasama->client_id)
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
