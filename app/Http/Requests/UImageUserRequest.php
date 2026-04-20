<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UImageUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
            'clients_id' => 'nullable|integer',
            'img_before' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:12288',
            'img_proccess' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:12288',
            'img_final' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:12288',
            'temp_img_before' => 'nullable|string',
            'temp_img_proccess' => 'nullable|string',
            'temp_img_final' => 'nullable|string',
            'area' => 'required|string|max:255',
            'note' => 'required|string',
            'max_data' => 'nullable',
            'status' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User wajib diisi.',
            'clients_id.required' => 'Mitra wajib diisi.',
            'img_before.required' => 'Foto Before wajib diisi.',
            'img_final.required' => 'Foto After wajib diisi.',
            'area.required' => 'Area wajib diisi.',
            'note.required' => 'Keterangan wajib diisi.',
        ];
    }
}
