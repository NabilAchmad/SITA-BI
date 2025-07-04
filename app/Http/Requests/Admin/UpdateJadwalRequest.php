<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
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
            'tanggalSidang' => 'required|date',
            'waktuMulai' => 'required|date_format:H:i',
            'waktuSelesai' => 'required|date_format:H:i|after:waktuMulai',
            'ruangan' => 'required|exists:ruangan,id',
            // Validasi untuk penguji, boleh kosong (nullable)
            'penguji1' => 'nullable|exists:dosen,id',
            'penguji2' => 'nullable|exists:dosen,id',
            'penguji3' => 'nullable|exists:dosen,id',
            'penguji4' => 'nullable|exists:dosen,id',
        ];
    }
}
