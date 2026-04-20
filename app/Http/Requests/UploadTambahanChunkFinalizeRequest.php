<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTambahanChunkFinalizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAccess();
    }

    public function rules(): array
    {
        return [
            'upload_id' => ['required', 'string'],
        ];
    }
}

