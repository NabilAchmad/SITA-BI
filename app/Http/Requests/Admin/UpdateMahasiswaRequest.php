<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Mahasiswa; // Pastikan model di-import

class UpdateMahasiswaRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        /**
         * ✅ PERBAIKAN: Ganti dengan permission yang benar.
         * Kita gunakan 'manage user accounts' yang sudah ada di database Anda
         * dan sudah kita pasang di middleware controller.
         */
        return $this->user()->can('manage user accounts');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        /**
         * ✅ PERBAIKAN: Ambil model Mahasiswa yang sudah di-resolve oleh Laravel.
         * Ketika menggunakan Route Model Binding, Laravel menyediakan model
         * sebagai properti dengan nama yang sama dengan parameter route.
         */
        $mahasiswa = $this->route('mahasiswa');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'prodi' => 'required|string|max:100',
            'password' => 'nullable|confirmed|min:8',
        ];
    }
}
