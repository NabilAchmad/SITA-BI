<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Logika ini memastikan hanya mahasiswa pemilik tugas akhir yang bisa mengupload.
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
     * Get the validation rules that apply to the request.
     * Aturan ini disesuaikan dengan input yang diharapkan oleh Service.
     */
    public function rules(): array
    {
        return [
            'file_bimbingan' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:25600'],
            'tipe_dokumen' => ['required', 'string', Rule::in(['bimbingan'])],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
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
