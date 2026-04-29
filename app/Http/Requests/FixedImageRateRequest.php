<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FixedImageRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upload_image_id' => ['required', 'integer', 'min:1'],
            'client_id' => ['nullable', 'integer', 'min:1'],
            'month' => ['nullable'],
            'year' => ['nullable'],
            'rating_value' => ['required', 'string', 'in:kurang,cukup,baik'],
            'rating_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

