<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadTambahanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAccess();
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.temp_token' => ['required', 'string'],
            'items.*.keterangan' => ['required', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Minimal satu file tambahan wajib diisi.',
            'items.min' => 'Minimal satu file tambahan wajib diisi.',
            'items.max' => 'Maksimal 30 file tambahan per submit.',
            'items.*.temp_token.required' => 'Token upload file wajib tersedia.',
            'items.*.keterangan.required' => 'Keterangan file wajib diisi.',
        ];
    }
}

