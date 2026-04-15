<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitChunkUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'field' => ['required', 'in:img_before,img_proccess,img_final'],
            'file_name' => ['required', 'string'],
            'file_size' => ['required', 'integer', 'min:1'],
            'mime_type' => ['required', 'string'],
            'total_chunks' => ['required', 'integer', 'min:1'],
        ];
    }
}
