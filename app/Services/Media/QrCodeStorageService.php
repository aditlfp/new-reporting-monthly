<?php

namespace App\Services\Media;

use App\Helpers\FileHelper;

class QrCodeStorageService
{
    public function delete(?string $path): void
    {
        if ($path) {
            FileHelper::deleteImage($path);
        }
    }
}
