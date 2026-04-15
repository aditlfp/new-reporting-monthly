<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateUploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required'],
            'note' => ['required', 'string'],
            'img_before' => ['nullable', 'file'],
            'img_proccess' => ['nullable', 'file'],
            'img_final' => ['nullable', 'file'],
        ];
    }
}
