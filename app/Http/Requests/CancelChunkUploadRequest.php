<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelChunkUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temp_token' => ['nullable', 'string'],
            'upload_id' => ['nullable', 'string'],
        ];
    }
}
