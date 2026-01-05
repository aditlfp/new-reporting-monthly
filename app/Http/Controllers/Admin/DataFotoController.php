<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\FixedImage;
use App\Models\UploadImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataFotoController extends Controller
{
    public function index(Request $request)
    {
        $fixedIMG = FixedImage::pluck('upload_image_id')->toArray();
        // dd($fixedIMG);
        $query = UploadImage::with('clients', 'user.kerjasama')->whereIn('id', $fixedIMG);
        $user = collect();

        if ($request->mitra) {
            $user = User::whereHas('kerjasama', function ($q) use ($request) {
                $q->where('client_id', $request->mitra);
            })->get();

            $filters = ['client_id' => $request->mitra];
            
            if ($request->user) {
                $filters['user_id'] = $request->user;
                $perPage = 30;
            } else {
                $perPage = 14;
            }

            $images = $query->searchFilters($filters)->paginate($perPage);

        } elseif ($request->month) {
            $images = $query->searchFilters([
                'month' => $request->month,
                'year' => $request->year
            ])->paginate(14);

        } elseif ($request->has('id')) {
            $images = $query->where('id', $request->input('id'))->first();

        } else {
            $images = $query->latest()->paginate(20);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $images,
                'users' => $user,
            ]);
        }

        $client = Clients::all();

        return view('pages.admin.fotoProgres.index', compact('images', 'client'));
    }

    public function update(Request $request, UploadImage $uploadImage)
    {
        $data = [
            'clients_id' => $request->client_id,
            'note'       => $request->note,
            'status'     => 1,
        ];

        $fileFields = [
            'img_before'   => 'upload_images/before',
            'img_proccess' => 'upload_images/process',
            'img_final'    => 'upload_images/final',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                // Delete old file if exists to keep storage clean
                if ($uploadImage->{$field}) {
                    Storage::disk('public')->delete($uploadImage->{$field});
                }
                
                $data[$field] = FileHelper::uploadImage($request->file($field), $path);
            }
        }

        $uploadImage->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Upload updated successfully',
                'data'    => $uploadImage,
            ]);
        }

        return redirect()->route('admin.upload.index')->with('success', 'Upload updated successfully.');
    }

    public function destroy(Request $request, UploadImage $uploadImage)
    {
        if ($uploadImage->img_before) Storage::disk('public')->delete($uploadImage->img_before);
        if ($uploadImage->img_proccess) Storage::disk('public')->delete($uploadImage->img_proccess);
        if ($uploadImage->img_final) Storage::disk('public')->delete($uploadImage->img_final);

        $uploadImage->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Upload deleted successfully']);
        }

        return redirect()->route('admin.upload.index')->with('success', 'Upload deleted successfully.');
    }

    public function getUsers(Request $request)
    {
        $mitraId = $request->mitra_id;
        
        $users = User::whereHas('kerjasama', function($query) use ($mitraId) {
            $query->where('client_id', $mitraId);
        })->get();
        
        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }
}
