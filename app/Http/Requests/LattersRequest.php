<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LattersRequest extends FormRequest
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
            'cover_id' => 'required|exists:covers,id',
            'latter_numbers' => 'required|string|max:255',
            'latter_matters' => 'required|string',
            'period' => 'required|string|max:255',
            'report_content' => 'nullable|string',
            'signature' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'cover_id.required' => 'Cover wajib diisi.',
            'latter_numbers.required' => 'No Surat Wajib diisi.',
            'latter_matters.required' => 'Hal Surat wajib diisi.',
            'period.required' => 'Periode wajib diisi.',
        ];
    }
}
