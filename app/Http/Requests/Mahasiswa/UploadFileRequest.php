<?php

// --- File: app/Http/Requests/Mahasiswa/UploadFileRequest.php ---
// Berfungsi untuk memvalidasi file yang diunggah oleh mahasiswa.

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\TugasAkhir|null $tugasAkhir */
        $tugasAkhir = $this->route('tugasAkhir');

        // Otorisasi berlapis untuk keamanan:
        // 1. Pastikan pengguna adalah seorang mahasiswa.
        // 2. Pastikan data Tugas Akhir ditemukan dari URL.
        // 3. Pastikan Tugas Akhir ini benar-benar milik mahasiswa yang sedang login.
        return $this->user()->hasRole('mahasiswa') &&
            $tugasAkhir &&
            $tugasAkhir->mahasiswa_id === $this->user()->mahasiswa->id;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:25600'], // Batas 25MB
            'jenis_dokumen' => ['nullable', 'string', 'max:50'], // Input opsional dari form
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Anda harus memilih sebuah file untuk diunggah.',
            'file.mimes'    => 'Format file yang diizinkan hanya PDF, DOC, atau DOCX.',
            'file.max'      => 'Ukuran file tidak boleh melebihi 25MB.',
        ];
    }
}
