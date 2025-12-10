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
            $image = UploadImage::with(['fixedImage'])->where('clients_id', $client->id)->where("status", 1)->get();

            return response()->json([
                'status' => true,
                'message' => 'Get All Required Data',
                'data' => [
                    'client' => $client,
                    'image' => $image
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

            return response()->json([
                'status' => true,
                'message' => 'Data Has Been created successfully',
            ], 200);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request". $e->getMessage(), 1);
        }
    }
}
