<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UImageUserDraftRequest extends FormRequest
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
            "user_id" => 'required', 
            "clients_id" => 'required', 
            "img_before" => 'nullable', 
            "img_proccess" => 'nullable', 
            "img_final" => 'nullable', 
            "note" => 'required', 
            "max_data" => 'nullable', 
            "status" => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            "user_id.required" => 'User wajib diisi.', 
            "clients_id.required" => 'Mitra wajib diisi.', 
            "note.required" => 'Keterangan wajib diisi.',
        ];
    }
}
