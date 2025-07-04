<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignPengujiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi ditangani oleh middleware di route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'penguji' => 'required|array|min:1|max:4',
            'penguji.*' => 'required|exists:dosen,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'penguji.required' => 'Setidaknya satu dosen penguji harus dipilih.',
            'penguji.*.exists' => 'Dosen yang dipilih tidak valid.',
        ];
    }
}
