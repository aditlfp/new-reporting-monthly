<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadImagePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pdf' => ['required', 'file', 'mimetypes:application/pdf', 'max:512000'],
            'month' => ['required', 'date_format:Y-m'],
            'client_ids' => ['required', 'integer', 'exists:dbAbsensi.clients,id'],
        ];
    }
}
