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
            'theme_mode' => ['required', 'in:dark,light'],
            'splash_on_login' => ['nullable', 'boolean'],
        ];
    }
}
