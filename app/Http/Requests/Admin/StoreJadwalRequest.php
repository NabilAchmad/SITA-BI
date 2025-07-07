<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     * ✅ PERBAIKAN: Menambahkan pemeriksaan izin langsung di sini.
     */
    public function authorize(): bool
    {
        // Memastikan hanya user dengan izin 'manage sidang' yang bisa melanjutkan.
        return $this->user()->can('manage sidang');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sidang_id' => ['required', 'exists:sidang,id'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'ruangan_id' => ['required', 'exists:ruangan,id'],
        ];
    }

    /**
     * Sesuaikan nama atribut untuk pesan error.
     */
    public function attributes(): array
    {
        return [
            'sidang_id' => 'Mahasiswa',
            'tanggal' => 'Tanggal Sidang',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'ruangan_id' => 'Ruangan',
        ];
    }
}
