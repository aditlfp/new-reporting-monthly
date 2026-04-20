<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTambahanChunkPartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAccess();
    }

    public function rules(): array
    {
        return [
            'upload_id' => ['required', 'string'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'chunk' => ['required', 'file', 'max:2048'],
        ];
    }
}

