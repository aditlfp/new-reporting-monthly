<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoverRequest extends FormRequest
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
        $rules = [
            'clients_id' => 'required',
            'jenis_rekap' => 'required|string',
        ];

        // For create, images are required
        if ($this->isMethod('post')) {
            $rules['img_src_1'] = 'required|image|mimes:jpeg,png,jpg,svg|max:2048';
            $rules['img_src_2'] = 'required|image|mimes:jpeg,png,jpg,svg|max:2048';
        } 
        // For update, images are required only if they are being changed
        elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // If img1_changed is set to 1, then img_src_1 is required
            if ($this->input('img1_changed') == 1) {
                $rules['img_src_1'] = 'required|image|mimes:jpeg,png,jpg,svg|max:2048';
            }
            if ($this->input('img2_changed') == 1) {
                $rules['img_src_2'] = 'required|image|mimes:jpeg,png,jpg,svg|max:2048';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'clients_id.required' => 'Mitra Wajib Diisi.',
            'jenis_rekap.required' => 'Jenis Rekap Wajib Diisi.',
            'img_src_1.required' => 'Logo 1 Wajib Diisi.',
            'img_src_2.required' => 'Logo 2 Wajib Diisi.',
            'img_src_1.image' => 'Logo 1 harus berupa gambar.',
            'img_src_2.image' => 'Logo 2 harus berupa gambar.',
            'img_src_1.mimes' => 'Logo 1 harus berformat: jpeg, png, jpg, svg.',
            'img_src_2.mimes' => 'Logo 2 harus berformat: jpeg, png, jpg, svg.',
            'img_src_1.max' => 'Logo 1 maksimal 2MB.',
            'img_src_2.max' => 'Logo 2 maksimal 2MB.',
        ];
    }
}
