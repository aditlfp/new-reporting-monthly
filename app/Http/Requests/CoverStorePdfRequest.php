<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoverStorePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pdf' => ['required', 'file', 'mimetypes:application/pdf', 'max:512000'],
            'cover_id' => ['required'],
            'srt_id' => ['required', 'integer'],
        ];
    }
}
