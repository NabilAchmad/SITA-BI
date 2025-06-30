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
        // Otorisasi sederhana, pastikan user memiliki peran 'kaprodi'
        // Middleware di route sudah menangani ini, tapi ini lapisan tambahan.
        return $this->user()->hasRole('kaprodi');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'catatan' => 'required|string|min:10',
        ];
    }
}
