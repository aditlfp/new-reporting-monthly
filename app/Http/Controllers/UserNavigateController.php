<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use Illuminate\Http\Request;

class UserNavigateController extends Controller
{
    public function toUploadImgLaporan()
    {
        $auth = auth()->user();
        $now = now();
        $uploadDraft = UploadImage::where('user_id', $auth->id)
                ->where('status', 0)
                ->whereMonth('created_at', $now->month)
                ->latest()
                ->first();
        $allImages = UploadImage::where('clients_id', $auth->kerjasama->client_id)
            ->where('status', 1)
            ->whereMonth('created_at', $now->month)
            ->latest()
            ->get();
        $imageCount = UploadImage::where('clients_id', $auth->kerjasama->client_id)
            ->whereMonth('created_at', $now->month)
            ->where('status', 1);

        // Count each image type separately
        $totalBefore = (clone $imageCount)
            ->whereNotNull('img_before')
            ->where('img_before', '!=', '')
            ->where('img_before', '!=', 'none')
            ->count();

        $totalProccess = (clone $imageCount)
            ->whereNotNull('img_proccess')
            ->where('img_proccess', '!=', '')
            ->where('img_proccess', '!=', 'none')
            ->count();

        $totalFinal = (clone $imageCount)
            ->whereNotNull('img_final')
            ->where('img_final', '!=', '')
            ->where('img_final', '!=', 'none')
            ->count();

        // Calculate the total number of images
        $totalImageCount = $totalBefore + $totalProccess + $totalFinal;

        return view('pages.user.send_img.create', compact('uploadDraft', 'totalImageCount', 'allImages'));
    }

    public function toCalenderUpload()
    {
        
    }
}
