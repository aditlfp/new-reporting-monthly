<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use App\Http\Requests\UImageUserRequest;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Models\Clients;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $images = UploadImage::where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(14);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $images,
            ]);
        }

        return view('upload_images.index', compact('images'));
    }

    public function store(UImageUserRequest $request)
    {
        $user = $request->user();

        // Monthly limit
        $uploadCount = UploadImage::where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        if ($uploadCount >= 14) {
            $message = 'Maksimal Upload Data sudah terpenuhi coba lagi bulan depan.';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : back()->with('error', $message);
        }

        $img_before   = FileHelper::uploadImage($request->file('img_before'), 'upload_images/before');
        $img_proccess = FileHelper::uploadImage($request->file('img_proccess'), 'upload_images/process');
        $img_final    = FileHelper::uploadImage($request->file('img_final'), 'upload_images/final');

        $upload = UploadImage::create([
            'user_id'      => $request->user_id,
            'clients_id'   => $request->clients_id,
            'img_before'   => $img_before,
            'img_proccess' => $img_proccess,
            'img_final'    => $img_final,
            'note'         => $request->note,
            'max_data'     => 14,
            'status'       => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload created successfully',
                'data' => $upload,
            ], 201);
        }

        return redirect()->route('upload-images.index')->with('success', 'Upload created successfully.');
    }

    public function draft(Request $request)
    {
        $user = $request->user();

        $request->validate([
            "user_id" => 'required',
            "clients_id" => 'required',
            "img_before" => 'nullable',
            "img_proccess" => 'nullable',
            "img_final" => 'nullable',
            "note" => 'required',
            "max_data" => 'nullable',
            "status" => 'nullable',
        ], [
            "user_id.required" => 'User wajib diisi.',
            "clients_id.required" => 'Mitra wajib diisi.',
            "note.required" => 'Keterangan wajib diisi.',
        ]);

        // Monthly limit
        $uploadCount = UploadImage::where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        if ($uploadCount >= 14) {
            $message = 'Maksimal Upload Data sudah terpenuhi coba lagi bulan depan.';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : back()->with('error', $message);
        }

        if($request->hasFile('img_before') || $request->hasFile('img_proccess') || $request->hasFile('img_final')) {
            $img_before   = FileHelper::uploadImage($request->file('img_before'), 'upload_images/before');
            $img_proccess = FileHelper::uploadImage($request->file('img_proccess'), 'upload_images/process');
            $img_final    = FileHelper::uploadImage($request->file('img_final'), 'upload_images/final');
        }
        

        $upload = UploadImage::create([
            'user_id'      => $request->user_id,
            'clients_id'   => $request->clients_id,
            'img_before'   => $img_before ?? 'none',
            'img_proccess' => $img_proccess ?? 'none',
            'img_final'    => $img_final ?? 'none',
            'note'         => $request->note,
            'max_data'     => 14,
            'status'       => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload created successfully',
                'data' => $upload,
            ], 201);
        }

        return redirect()->route('upload-images.index')->with('success', 'Upload created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, UploadImage $uploadImage)
    {
        $user = auth()->user();

        if ($uploadImage->clients_id !== $user->clients_id || $uploadImage->user_id !== $user->id) {
            $message = 'Unauthorized';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : abort(403, $message);
        }

         if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Get Data By ID',
                'data' => $uploadImage,
            ], 201);
        }

        return view('upload_images.show', compact('uploadImage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "note" => 'required',
            "status" => 'nullable',
        ], [
            "note.required" => 'Keterangan wajib diisi.',
        ]);

        $user = $request->user();
        $uploadImage = UploadImage::findOrFail($id);
        
        // Authorization check
        if ($uploadImage->clients_id !== $user->kerjasama->client_id || $uploadImage->user_id !== $user->id) {
            $message = 'Unauthorized';
            
            return $request->ajax()
            ? response()->json(['message' => $message], 403)
            : abort(403, $message);
        }
        
        // Check if required images (img_before and img_final) are provided
        $hasBeforeImage = false;
        $hasFinalImage = false;
        
        // Check for new files
        if ($request->hasFile('img_before')) {
            $hasBeforeImage = true;
        } else if ($request->has('existing_img_before') && !empty($request->input('existing_img_before')) && $request->input('existing_img_before') != 'none') {
            $hasBeforeImage = true;
        } else if ($request->input('type') === 'draft') {
            $hasBeforeImage = true; // Allow missing img_before for drafts
        }
        
        if ($request->hasFile('img_final')) {
            $hasFinalImage = true;
        } else if ($request->has('existing_img_final') && !empty($request->input('existing_img_final')) && $request->input('existing_img_final') != 'none') {
            $hasFinalImage = true;
        } else if ($request->input('type') === 'draft') {
            $hasFinalImage = true; // Allow missing img_final for drafts
        }
        
        // If required images are not found, return error
        if (!$hasBeforeImage) {
            $message = 'Gambar sebelum (before) wajib disertakan.';
            
            return $request->ajax()
            ? response()->json(['message' => $message], 422)
            : redirect()->back()->with('error', $message);
        }
        
        if (!$hasFinalImage) {
            $message = 'Gambar akhir (final) wajib disertakan.';
            
            return $request->ajax()
            ? response()->json(['message' => $message], 422)
            : redirect()->back()->with('error', $message);
        }
        
        // Prepare data for update
        $updateData = [
            'note' => $request->note,
            'status' => $request->status, // Include status in the update
        ];
        
        // Handle img_before
        if ($request->hasFile('img_before')) {
            $updateData['img_before'] = FileHelper::uploadImage(
                $request->file('img_before'), 
                'upload_images/before', 
                $uploadImage->img_before
            );
        } else if ($request->has('existing_img_before') && !empty($request->input('existing_img_before'))) {
            $updateData['img_before'] = $request->input('existing_img_before');
        } else if ($request->input('type') === 'draft') {
            // If no new file and no existing image, set to null for drafts
            $updateData['img_before'] = 'none';
            
        }
        
        // Handle img_proccess (optional)
        if ($request->hasFile('img_proccess')) {
            $updateData['img_proccess'] = FileHelper::uploadImage(
                $request->file('img_proccess'), 
                'upload_images/process', 
                $uploadImage->img_proccess
            );
        } else if ($request->has('existing_img_proccess') && !empty($request->input('existing_img_proccess'))) {
            $updateData['img_proccess'] = $request->input('existing_img_proccess');
        } else if ($request->input('type') === 'draft') {
            // If no new file and no existing image, set to null for drafts
            $updateData['img_proccess'] = 'none';
           
        }

        // Handle img_final
        if ($request->hasFile('img_final')) {
            $updateData['img_final'] = FileHelper::uploadImage(
                $request->file('img_final'), 
                'upload_images/final', 
                $uploadImage->img_final
            );
        } else if ($request->has('existing_img_final') && !empty($request->input('existing_img_final'))) {
            $updateData['img_final'] = $request->input('existing_img_final');
        } else if ($request->input('type') === 'draft') {
            // If no new file and no existing image, set to null for drafts
            $updateData['img_final'] = 'none';
            
        }
        
        // Update the record
        $uploadImage->update($updateData);

        // Return appropriate response
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Upload updated successfully',
                'data' => $uploadImage,
            ]);
        }

        return redirect()->route('upload-images.index')->with('success', 'Upload updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UploadImage $uploadImage)
    {
        $user = $request->user();

        if ($uploadImage->clients_id !== $user->clients_id || $uploadImage->user_id !== $user->id) {
            $message = 'Unauthorized';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : abort(403, $message);
        }

        if ($uploadImage->img_before) Storage::disk('public')->delete($uploadImage->img_before);
        if ($uploadImage->img_proccess) Storage::disk('public')->delete($$uploadImage->img_proccess);
        if ($uploadImage->img_final) Storage::disk('public')->delete($uploadImage->img_final);

        $uploadImage->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Upload deleted successfully']);
        }

        return redirect()->route('upload-images.index')->with('success', 'Upload deleted successfully.');
    }

    public function adminIndex(Request $request)
    {
        if($request->has('id')){
            $images = UploadImage::with('clients')->where('id', $request->input('id'))->first();
        } else {
            $images = UploadImage::with('clients')->latest()->paginate(20);
        }

        $client = Clients::all();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $images,
            ]);
        }

        return view('pages.admin.fotoProgres.index', compact('images', 'client'));
    }

    public function adminUpdate(Request $request, UploadImage $uploadImage)
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

    public function adminDestroy(Request $request, UploadImage $uploadImage)
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

    public function getPdfData(Request $request)
    {
        $query = UploadImage::with('clients');
        
        if ($request->has('ids') && !empty($request->ids)) {
            $query->whereIn('id', $request->ids);
        } elseif ($request->has('month') && !empty($request->month)) {
            $query->whereMonth('created_at', date('m', strtotime($request->month)))
                ->whereYear('created_at', date('Y', strtotime($request->month)));
        }
        
        $data = $query->get();
        
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function storePdf(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimetypes:application/pdf|max:512000', // 500MB
            'month' => 'required',
            'client_ids' => 'required' // Add validation for client_ids
        ]);

        // Parse the month to get year and month
        $date = Carbon::parse($request->month);
        $year = $date->format('Y');
        $month = $date->format('m');
        
        // Get client IDs and sort them for consistency
        $clientIds = $request->client_ids;
        
        
        // Format the filename as year-month-client_id
        $fileName = $year . '-' . $month . '-' . $clientIds . '.pdf';
        $filePath = 'rekap_foto/' . $fileName;

        // Store the PDF file
        Storage::disk('public')->put($filePath, file_get_contents($request->file('pdf')->getRealPath()));

        return response()->json([
            'status' => true,
            'message' => 'PDF stored successfully',
            'file_path' => Storage::url($filePath)
        ]);
    }
}
