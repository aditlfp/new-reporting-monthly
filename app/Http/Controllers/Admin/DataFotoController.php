<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\UploadImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataFotoController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('id')){
            $images = UploadImage::with('clients', 'user.kerjasama')->where('id', $request->input('id'))->first();
        } else {
            $images = UploadImage::with('clients', 'user.kerjasama')->latest()->paginate(20);
        }

        if($request->month)
        {
            $images = UploadImage::with('clients', 'user.kerjasama')->searchFilters([
                'month' => $request->month,
                'year' => $request->year
            ])->paginate(14);
        }

        $user = collect();

        if($request->mitra)
        {
            $user = User::whereHas('kerjasama', function($q) use ($request) {
                $q->where('client_id', $request->mitra);
            })->get();

            if($request->user) {
                $images = UploadImage::with('clients', 'user.kerjasama')->searchFilters([
                    'client_id' => $request->mitra,
                    'user_id' => $request->user,
                ])->paginate(30);
            } else {
                $images = UploadImage::with('clients', 'user.kerjasama')->searchFilters([
                    'client_id' => $request->mitra,
                ])->paginate(14);
            }
        }

        $client = Clients::all();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $images,
                'users' => $user,
            ]);
        }

        return view('pages.admin.fotoProgres.index', compact('images', 'client'));
    }

    public function update(Request $request, UploadImage $uploadImage)
    {
        
        if($request->hasFile('img_before') || $request->hasFile('img_proccess') || $request->hasFile('img_final')) {
            $img_before   = FileHelper::uploadImage($request->file('img_before'), 'upload_images/before');
            $img_proccess = FileHelper::uploadImage($request->file('img_proccess'), 'upload_images/process');
            $img_final    = FileHelper::uploadImage($request->file('img_final'), 'upload_images/final');
        }
        
        $updateData = [
            'clients_id' => $request->client_id,
            'img_before' => $img_before ?? $uploadImage->img_before,
            'img_proccess' => $img_proccess ?? $uploadImage->img_proccess,
            'img_final' => $img_final ?? $uploadImage->img_final,
            'note' => $request->note,
            'status' => 1,
        ];
        // dd($updateData);

        $uploadImage->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload updated successfully',
                'data' => $uploadImage,
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
