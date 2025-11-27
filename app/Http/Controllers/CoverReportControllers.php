<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CoverRequest;
use App\Helpers\FileHelper;
use App\Models\Clients;
use App\Models\Cover;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CoverReportControllers extends Controller
{
    public function index(Request $request)
    {
        $covers = Cover::with('client')->latest()->paginate(10);
        $client = Clients::all();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $covers,
                'client' => $client
            ]);
        }

        return view('pages.admin.covers.index', compact('covers', 'client'));
    }

    public function show(Request $request, $id)
    {
        try {
            $covers = Cover::with('client')->findOrFail($id);

            // If AJAX → return JSON data
            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Get cover by ID.',
                    'data' => $covers
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => $e
            ], 500);
        }

    }

    public function store(CoverRequest $request)
    {
        try {
            $validated = $request->validated();

            $validated['img_src_1'] = $this->handleImageUpload($request, 'img_src_1', null);
            $validated['img_src_2'] = $this->handleImageUpload($request, 'img_src_2', null);

            $cover = Cover::create($validated);

            $cover->load('client');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $cover->id,
                    'jenis_rekap' => $cover->jenis_rekap,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->getErrorResponse($e, 'Failed to create cover.');
        }
    }

    public function update(CoverRequest $request, $id)
    {
        try {
            $cover = Cover::findOrFail($id);
            $validated = $request->validated();

            if ($request->hasFile('img_src_1') && $request->img1_changed) {
                $validated['img_src_1'] = $this->handleImageUpload($request, 'img_src_1', $cover->img_src_1);
            }
            if ($request->hasFile('img_src_2') && $request->img2_changed) {
                $validated['img_src_2'] = $this->handleImageUpload($request, 'img_src_2', $cover->img_src_2);
            }

            $cover->update($validated);

            $cover->load('client');

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $cover->id,
                    'client_name' => ucwords(strtolower($cover->client->name)),
                    'jenis_rekap' => $cover->jenis_rekap,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->getErrorResponse($e, 'Failed to update cover.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $cover = Cover::findOrFail($id);
            
            // Delete images
            if ($cover->img_src_1) {
                FileHelper::deleteImage($cover->img_src_1);
            }
            if ($cover->img_src_2) {
                FileHelper::deleteImage($cover->img_src_2);
            }
            
            $cover->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Cover deleted successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cover'
            ], 500);
        }
    }

    private function handleImageUpload(Request $request, $fieldName, $oldImagePath = null)
    {
        if ($request->hasFile($fieldName)) {
            // Delete old image if exists
            if ($oldImagePath) {
                FileHelper::deleteImage($oldImagePath);
            }
            return FileHelper::uploadImage($request->file($fieldName), 'covers');
        }
        return $oldImagePath;
    }

    private function getErrorResponse(\Exception $e, $message)
    {
        Log::error($message . ': ' . $e->getMessage());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message . ': ' . $e->getMessage()
            ], 500);
        }

        return back()->withInput()->withErrors(['error' => $message . ': ' . $e->getMessage()]);
    }
    
}
