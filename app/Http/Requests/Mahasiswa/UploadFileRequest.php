<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Import Rule

class UploadFileRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\TugasAkhir|null $tugasAkhir */
        $tugasAkhir = $this->route('tugasAkhir');

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

            // Validasi diperketat untuk memastikan integritas data
            'jenis_dokumen' => [
                'required',
                'string',
                // Hanya izinkan nilai yang ada di ENUM database
                Rule::in(['proposal', 'draft', 'final', 'lainnya']),
            ],
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
            'jenis_dokumen.required' => 'Anda harus menentukan jenis dokumen.',
            'jenis_dokumen.in' => 'Jenis dokumen yang dipilih tidak valid.',
        ];
    }
}
