<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_key' => ['nullable', 'string'],
            'theme' => ['nullable', 'string', 'max:100'],
            'login_by' => ['nullable', 'string', 'max:100'],
        ];
    }
}
