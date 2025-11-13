<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Upload an image dynamically.
     *
     * @param  \Illuminate\Http\UploadedFile|null  $file
     * @param  string  $folder
     * @param  string|null  $oldFile
     * @param  bool  $useOriginalName
     * @return string|null
     */
    public static function uploadImage(?UploadedFile $file, string $folder = 'uploads', ?string $oldFile = null, bool $useOriginalName = false)
    {
        if (!$file) {
            return $oldFile; 
        }

        // delete old file if exists
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        // dynamic filename
        $filename = $useOriginalName
            ? $file->getClientOriginalName()
            : Str::uuid() . '.' . $file->getClientOriginalExtension();

        // store in public disk
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }

    /**
     * Delete an image from storage
     *
     * @param string $path
     * @return bool
     */
    public static function deleteImage($path)
    {
        if (empty($path)) {
            return false;
        }

        try {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
            
            // If not in storage, check if it's a direct path
            $fullPath = public_path($path);
            if (File::exists($fullPath)) {
                return File::delete($fullPath);
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to delete image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the full URL for an image path
     *
     * @param string $path
     * @return string
     */
    public static function getImageUrl($path)
    {
        if (empty($path)) {
            return asset('img/placeholder.png'); // Return a placeholder image
        }

        // If path already includes /storage/, return as is
        if (str_contains($path, '/storage/')) {
            return asset($path);
        }

        // If path is from storage, generate URL
        return asset('storage/' . $path);
    }

    /**
     * Delete multiple images
     *
     * @param array $paths
     * @return array
     */
    public static function deleteMultipleImages($paths)
    {
        $results = [];
        
        foreach ($paths as $path) {
            $results[$path] = self::deleteImage($path);
        }
        
        return $results;
    }
}
