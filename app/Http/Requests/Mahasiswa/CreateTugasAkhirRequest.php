<?php

// --- File: app/Http/Requests/Mahasiswa/CreateTugasAkhirRequest.php ---
// Berfungsi untuk memvalidasi data saat mahasiswa mengajukan judul TA baru.

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class CreateTugasAkhirRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        // Pastikan hanya pengguna dengan peran 'mahasiswa' yang bisa mengajukan.
        return $this->user()->hasRole('mahasiswa');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255|unique:tugas_akhir,judul',
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas akhir tidak boleh kosong.',
            'judul.unique'   => 'Judul ini sudah pernah diajukan. Silakan gunakan judul lain.',
        ];
    }
}
