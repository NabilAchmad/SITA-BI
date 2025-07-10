<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignPengujiRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        /**
         * ✅ PERBAIKAN KRITIS: Tambahkan otorisasi di sini.
         * Ini adalah lapisan keamanan kedua setelah middleware di route.
         * Hanya pengguna dengan peran yang tepat yang bisa melanjutkan.
         */
        return $this->user()->hasAnyRole(['admin', 'kajur', 'kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'penguji' => 'required|array|min:1|max:4',

            /**
             * ✅ PENINGKATAN: Tambahkan aturan 'distinct'.
             * Ini memastikan setiap ID dosen di dalam array harus unik.
             */
            'penguji.*' => 'required|distinct|exists:dosen,id',
        ];
    }

    /**
     * Dapatkan pesan kustom untuk error validator.
     */
    public function messages(): array
    {
        return [
            'penguji.required' => 'Setidaknya satu dosen penguji harus dipilih.',
            'penguji.*.exists' => 'Dosen yang dipilih tidak valid.',

            // ✅ PENINGKATAN: Tambahkan pesan untuk aturan 'distinct'.
            'penguji.*.distinct' => 'Dosen yang sama tidak boleh dipilih lebih dari satu kali.',
        ];
    }
}
