<?php

namespace App\Http\Requests\Mahasiswa;

use App\Models\TugasAkhir;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Ambil ID Tugas Akhir dari parameter route
        $tugasAkhirId = $this->route('id');

        // Cari Tugas Akhir berdasarkan ID
        $tugasAkhir = TugasAkhir::find($tugasAkhirId);

        // Otorisasi:
        // 1. Pastikan Tugas Akhir ada.
        // 2. Pastikan Tugas Akhir ini milik mahasiswa yang sedang login.
        return $tugasAkhir && $tugasAkhir->mahasiswa_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file_proposal' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:25600', // 25MB
            ],
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'file_proposal.required' => 'Anda harus memilih file proposal untuk diunggah.',
            'file_proposal.mimes'    => 'Format file harus PDF, DOC, atau DOCX.',
            'file_proposal.max'      => 'Ukuran file tidak boleh lebih dari 25MB.',
        ];
    }
}
