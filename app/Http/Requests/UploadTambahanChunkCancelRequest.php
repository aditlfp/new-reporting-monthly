<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTambahanChunkCancelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAccess();
    }

    public function rules(): array
    {
        return [
            'temp_token' => ['nullable', 'string', 'required_without:upload_id'],
            'upload_id' => ['nullable', 'string', 'required_without:temp_token'],
        ];
    }
}

