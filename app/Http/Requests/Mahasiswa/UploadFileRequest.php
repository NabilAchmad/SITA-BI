<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadFileRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     * Logika ini sudah benar, tidak perlu diubah.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\TugasAkhir|null $tugasAkhir */
        $tugasAkhir = $this->route('tugasAkhir'); // Pastikan nama parameter di route adalah 'tugasAkhir'

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
            // ✅ PERBAIKAN 1: Mengubah nama field dari 'file' menjadi 'file_bimbingan'
            'file_bimbingan' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:25600'], // Batas 25MB

            // ✅ PERBAIKAN 2: Mengubah nama field dari 'jenis_dokumen' menjadi 'tipe_dokumen'
            'tipe_dokumen' => [
                'required',
                'string',
                Rule::in(['proposal', 'draft', 'final', 'lainnya']),
            ],

            // Menambahkan validasi untuk catatan (opsional)
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'file_bimbingan.required' => 'Anda harus memilih sebuah file untuk diunggah.',
            'file_bimbingan.mimes'    => 'Format file yang diizinkan hanya PDF, DOC, atau DOCX.',
            'file_bimbingan.max'      => 'Ukuran file tidak boleh melebihi 25MB.',
            'tipe_dokumen.required'   => 'Anda harus menentukan tipe dokumen.',
            'tipe_dokumen.in'         => 'Tipe dokumen yang dipilih tidak valid.',
        ];
    }
}
