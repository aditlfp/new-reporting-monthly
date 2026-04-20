<?php

namespace App\Services;

use App\Helpers\FileHelper;

class UploadTambahanStorageService
{
    public const ALLOWED_MIME_TO_EXTENSION = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function initChunkUpload(int $userId, string $fileName, int $fileSize, string $mimeType, int $totalChunks): string
    {
        $uploadId = (string) \Illuminate\Support\Str::uuid();
        FileHelper::storeChunkMetadata(
            $userId,
            $uploadId,
            'upload_tambahan',
            $fileName,
            $fileSize,
            $mimeType,
            $totalChunks,
        );

        return $uploadId;
    }

    public function storeChunkPart(int $userId, string $uploadId, int $chunkIndex, string $binary): array
    {
        $meta = FileHelper::getChunkMetadata($userId, $uploadId);
        FileHelper::storeChunkPart($userId, $uploadId, $chunkIndex, $binary);

        return $meta;
    }

    public function finalizeChunkUpload(int $userId, string $uploadId): array
    {
        return FileHelper::finalizeChunkUploadWithMimeMap($userId, $uploadId, self::ALLOWED_MIME_TO_EXTENSION);
    }

    public function cancelChunkUpload(int $userId, ?string $tempToken, ?string $uploadId): void
    {
        if (!empty($tempToken)) {
            FileHelper::deleteTempUpload($tempToken);
            return;
        }

        if (!empty($uploadId)) {
            FileHelper::deleteTempUploadByUploadId($userId, $uploadId);
        }
    }

    public function storeFromTempToken(string $tempToken, string $folder): string
    {
        return (string) FileHelper::moveTempUploadToPublicWithMimeMap(
            $tempToken,
            $folder,
            self::ALLOWED_MIME_TO_EXTENSION,
        );
    }
}

