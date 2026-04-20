<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;

class LetterStorageService
{
    public function storeSignature(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        return $file->storeAs('report_files', $filename, 'public');
    }
}
