<?php

namespace App\Http\Requests\Kaprodi;

use Illuminate\Foundation\Http\FormRequest;

class RejectTugasAkhirRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Otorisasi utama akan ditangani oleh Policy atau di controller.
        // Di sini kita pastikan user adalah salah satu Kaprodi.
        return $this->user()->hasAnyRole(['kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'alasan_penolakan' => 'required|string|min:10|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal harus 10 karakter.',
            'alasan_penolakan.max' => 'Alasan penolakan tidak boleh lebih dari 500 karakter.',
        ];
    }
}
