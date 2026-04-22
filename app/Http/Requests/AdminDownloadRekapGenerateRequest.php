<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDownloadRekapGenerateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover_id' => ['required', 'integer', 'exists:covers,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'pdf' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:512000'],
        ];
    }
}
