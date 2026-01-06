<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use setasign\Fpdi\Fpdi;
use Imagick;

class FileHelper
{
    protected static function imageManager(): ImageManager
    {
        return new ImageManager(new Driver());
    }

    protected static function shouldConvertToWebp(string $path): bool
    {
        if (!is_file($path)) {
            return false;
        }

        $sizeKB = filesize($path) / 1024;
        [$width, $height] = getimagesize($path);

        return !($sizeKB < 80 || ($width < 400 && $height < 400));
    }

    protected static function convertToWebp(string $fullPath, int $quality): ?string
    {
        if (!self::shouldConvertToWebp($fullPath)) {
            return null;
        }

        try {
            $manager = self::imageManager();
            $image   = $manager->read($fullPath);

            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);

            $image
                ->scale(height: 2048)
                ->encode(new WebpEncoder(quality: $quality))
                ->save($webpPath);

            if (filesize($webpPath) >= filesize($fullPath)) {
                unlink($webpPath);
                return null;
            }

            unlink($fullPath);

            return $webpPath;

        } catch (\Throwable $e) {
            Log::error('WebP conversion failed', [
                'file' => $fullPath,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
    /**
     * Upload an image dynamically.
     *
     * @param  \Illuminate\Http\UploadedFile|null  $file
     * @param  string  $folder
     * @param  string|null  $oldFile
     * @param  bool  $useOriginalName
     * @return string|null
     */
    public static function uploadImage(
        ?UploadedFile $file,
        string $folder = 'uploads',
        ?string $oldFile = null,
        bool $useOriginalName = false,
        int $quality = 80,
    ) {
        if (!$file) {
            return $oldFile;
        }

        
        try {
            // hapus file lama
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');

            $filename = $useOriginalName
                ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $ext
                : Str::uuid() . '.' . $ext;


            $path = $file->storeAs($folder, $filename, 'public');
            $fullPath = storage_path("app/public/{$path}");

            $webpPath = self::convertToWebp($fullPath, $quality);

            return $webpPath
                ? str_replace(storage_path('app/public/') , '', $webpPath)
                : $path;

        } catch (\Throwable $e) {
            Log::error('Upload image failed: ' . $e->getMessage());
            return null;
        }
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
            Log::error('Failed to delete image: ' . $e->getMessage());
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


    public static function mergePdfs($files, $mergedFilePath)
    {
        $pdf = new FPDI();

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        return $pdf->Output($mergedFilePath, 'F');
    }

    public static function lighten_color($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, $r + 255 * ($percent / 100));
        $g = min(255, $g + 255 * ($percent / 100));
        $b = min(255, $b + 255 * ($percent / 100));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

}
