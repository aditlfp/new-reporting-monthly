<?php

namespace App\Services\Media;

use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;

class CoverStorageService
{
    public function storeLogo(?UploadedFile $file, ?string $oldPath = null): ?string
    {
        if (!$file) {
            return $oldPath;
        }

        if ($oldPath) {
            FileHelper::deleteImage($oldPath);
        }

        return FileHelper::uploadImage($file, 'covers');
    }

    public function delete(?string $path): void
    {
        if ($path) {
            FileHelper::deleteImage($path);
        }
    }
}
