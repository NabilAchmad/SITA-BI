<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranSidangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Izinkan semua pengguna yang terotentikasi untuk membuat request ini.
        // Anda bisa menambahkan logika otorisasi yang lebih spesifik jika perlu.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Validasi untuk setiap file yang diunggah
            'file_naskah_ta'    => 'required|file|mimes:pdf,doc,docx|max:10240', // Maks 10MB
            'file_toeic'        => 'required|file|mimes:pdf,doc,docx|max:2048',      // Maks 2MB
            'file_rapor'        => 'required|file|mimes:pdf,doc,docx|max:5120',      // Maks 5MB
            'file_ijazah_slta'  => 'required|file|mimes:pdf,doc,docx|max:2048',      // Maks 2MB
            'file_bebas_jurusan' => 'required|file|mimes:pdf,doc,docx|max:2048',      // Maks 2MB
        ];
    }

    /**
     * Pesan kustom untuk error validasi.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'required' => 'File :attribute wajib diunggah.',
            'mimes'    => 'File :attribute harus dalam format yang diizinkan.',
            'max'      => 'Ukuran file :attribute terlalu besar.',
        ];
    }
}
