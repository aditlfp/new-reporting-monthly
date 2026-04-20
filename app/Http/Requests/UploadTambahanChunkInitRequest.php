<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTambahanChunkInitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAccess();
    }

    public function rules(): array
    {
        return [
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'integer', 'min:1', 'max:52428800'],
            'mime_type' => ['required', 'in:application/pdf,image/jpeg,image/png,image/webp'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:400'],
        ];
    }
}

