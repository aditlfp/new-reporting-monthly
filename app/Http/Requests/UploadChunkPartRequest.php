<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadChunkPartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
