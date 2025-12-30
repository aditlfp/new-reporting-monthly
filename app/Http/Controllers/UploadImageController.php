<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use App\Http\Requests\UImageUserRequest;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Http\Requests\UImageUserDraftRequest;
use App\Services\UploadImageService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();;
    }

    public function index(Request $request)
    {        
        $user = auth()->user();

        $images = UploadImage::where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(14);
        $draft = UploadImage::where('clients_id', $user->clients_id)
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->latest()
            ->first();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $images,
                'draft' => $draft,
            ]);
        }

        return view('upload_images.index', compact('images'));
    }

    public function countData(Request $request)
    {
            $uploadDraft = UploadImage::where('user_id', $this->user->id)
                ->where('status', 0)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            return response()->json([
                'status' => true,
                'data' => $uploadDraft,
            ]);
    
    }

    public function store(UImageUserRequest $request, UploadImageService $service
    ) 
    {
        try {
            $upload = $service->store($request);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Upload created successfully',
                    'data' => $upload,
                ], 201);
            }

            return redirect()
                ->route('upload-images.index')
                ->with('success', 'Upload created successfully.');

        } catch (\Exception $e) {

            Log::error('message: error on UploadImageController ', $e);
            return $request->ajax()
                ? response()->json([
                    'status' => false,
                    'message' => 'Failed to created data.',
                    'error' => $e->getMessage()
                ], 422)
                : back()->with('error', 'Failed to created data.');
        }
    }


    public function draft(UImageUserDraftRequest $request, UploadImageService $service
    ) 
    {
        try {
            $upload = $service->storeDraft($request);

            return $request->ajax()
                ? response()->json([
                    'status' => true,
                    'message' => 'Draft saved successfully',
                    'data' => $upload,
                ], 201)
                : redirect()
                    ->route('upload-images.index')
                    ->with('success', 'Draft saved successfully.');

        } catch (\Exception $e) {

            Log::error('message: error on UploadImageController::draft ', $e);
            return $request->ajax()
                ? response()->json([
                    'status' => false,
                    'message' => 'Failed to created data.',
                    'error' => $e->getMessage()
                ], 422)
                : back()->with('error', 'Failed to created data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, UploadImage $uploadImage)
    {
        $user = auth()->user();

        if ($uploadImage->clients_id != $user->clients_id || $uploadImage->user_id != $user->id) {
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
        if ($uploadImage->clients_id != $user->kerjasama->client_id || $uploadImage->user_id != $user->id) {
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

        if ($uploadImage->clients_id != $user->clients_id || $uploadImage->user_id != $user->id) {
            $message = 'Unauthorized';

            return $request->ajax()
                ? response()->json(['message' => $message], 403)
                : abort(403, $message);
        }

        if ($uploadImage->img_before) Storage::disk('public')->delete($uploadImage->img_before);
        if ($uploadImage->img_proccess) Storage::disk('public')->delete($uploadImage->img_proccess);
        if ($uploadImage->img_final) Storage::disk('public')->delete($uploadImage->img_final);

        $uploadImage->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Upload deleted successfully']);
        }

        return redirect()->route('upload-images.index')->with('success', 'Upload deleted successfully.');
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
