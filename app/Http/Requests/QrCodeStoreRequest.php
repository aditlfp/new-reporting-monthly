<?php

namespace App\Http\Requests;

use App\Services\Media\QrCodeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'kegiatan' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'data' => trim((string) $this->input('data')),
            'kegiatan' => trim((string) $this->input('kegiatan')) ?: null,
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $storedData = QrCodeService::combineData(
                (string) $this->input('data'),
                $this->input('kegiatan')
            );

            if (mb_strlen($storedData) > 255) {
                $validator->errors()->add(
                    'kegiatan',
                    'Gabungan data area dan kegiatan maksimal 255 karakter.'
                );
            }
        });
    }
}
