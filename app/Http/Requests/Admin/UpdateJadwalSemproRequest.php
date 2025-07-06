<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJadwalSemproRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Ganti dengan logika otorisasi Anda, misalnya:
        // return auth()->check() && auth()->user()->hasRole('admin');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tanggalSidang' => 'required|date',
            'waktuMulai'    => 'required|date_format:H:i',
            'waktuSelesai'  => 'required|date_format:H:i|after:waktuMulai',
            'ruangan'       => 'required|exists:ruangan,id',
            'penguji'       => 'present|array', // Pastikan 'penguji' ada di request, meskipun kosong
            'penguji.*'     => 'nullable|distinct|exists:dosen,id', // Setiap penguji harus unik dan ada di tabel dosen
        ];
    }

    /**
     * Get the custom validation messages for the defined rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tanggalSidang.required' => 'Tanggal sidang wajib diisi.',
            'waktuMulai.required'    => 'Waktu mulai sidang wajib diisi.',
            'waktuSelesai.required'  => 'Waktu selesai sidang wajib diisi.',
            'waktuSelesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'ruangan.required'       => 'Ruangan sidang wajib dipilih.',
            'penguji.present'        => 'Data penguji tidak valid.',
            'penguji.*.distinct'     => 'Tidak boleh memilih dosen penguji yang sama.',
            'penguji.*.exists'       => 'Dosen penguji yang dipilih tidak valid.',
        ];
    }
}
