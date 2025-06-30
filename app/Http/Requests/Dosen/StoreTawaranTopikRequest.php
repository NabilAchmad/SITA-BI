<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class StoreTawaranTopikRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan semua dosen yang terautentikasi untuk membuat tawaran
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
            // PERBAIKAN: Mengganti nama 'judul' menjadi 'judul_topik' agar konsisten
            'judul_topik' => 'required|string|max:255',
            // PERBAIKAN: Mengubah 'nullable' menjadi 'required' sesuai skema DB
            'deskripsi' => 'required|string',
            'kuota' => 'required|integer|min:0',
            // DIHAPUS: Menghapus aturan 'status' karena tidak ada di skema DB
        ];
    }
}
