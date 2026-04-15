<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
            'status' => ['nullable', 'integer'],
            'type' => ['nullable', 'string'],
            'img_before' => ['nullable', 'file'],
            'img_proccess' => ['nullable', 'file'],
            'img_final' => ['nullable', 'file'],
            'temp_img_before' => ['nullable', 'string'],
            'temp_img_proccess' => ['nullable', 'string'],
            'temp_img_final' => ['nullable', 'string'],
            'existing_img_before' => ['nullable', 'string'],
            'existing_img_proccess' => ['nullable', 'string'],
            'existing_img_final' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => 'Keterangan wajib diisi.',
        ];
    }
}
