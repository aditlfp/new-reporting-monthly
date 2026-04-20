<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSettingsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bg_color' => ['nullable', 'string', 'max:20'],
            'text_color_1' => ['nullable', 'string', 'max:20'],
            'text_color_2' => ['nullable', 'string', 'max:20'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'error_color' => ['nullable', 'string', 'max:20'],
        ];
    }
}
