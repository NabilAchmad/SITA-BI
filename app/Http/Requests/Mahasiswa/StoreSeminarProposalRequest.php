<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSeminarProposalRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak membuat request ini.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Hanya user yang sudah login dan berperan sebagai mahasiswa yang boleh melanjutkan.
        // Anda bisa menambahkan logika pengecekan peran (role) di sini jika ada.
        return Auth::check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pindahkan aturan validasi dari controller ke sini.
        return [
            'judul_proposal' => 'required|string|max:255',
        ];
    }

    /**
     * (Opsional) Pesan kustom untuk error validasi.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'judul_proposal.required' => 'Judul proposal tidak boleh kosong.',
            'judul_proposal.max' => 'Judul proposal tidak boleh lebih dari 255 karakter.',
        ];
    }
}
