<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'string', 'max:255'],
        ];
    }
}
