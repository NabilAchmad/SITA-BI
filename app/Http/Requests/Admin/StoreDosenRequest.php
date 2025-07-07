<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Penting: Jangan lupa import Rule

class StoreDosenRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     * ✅ Menggunakan permission lebih fleksibel daripada role.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage user accounts');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     * ✅ Menggunakan nama role lebih aman daripada ID.
     */
    public function rules(): array
    {
        return [
            'nama'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'nidn'      => ['required', 'string', 'max:50', 'unique:dosen,nidn'],
            
            // Validasi berdasarkan nama role yang valid dan diizinkan
            'role_name' => [
                'nullable', // Boleh kosong jika hanya dosen biasa
                'string',
                Rule::exists('roles', 'name')->whereIn('name', [
                    'kajur', 
                    'kaprodi-d3', 
                    'kaprodi-d4', 
                    'dosen'
                ])
            ],
        ];
    }

    /**
     * Sesuaikan nama atribut untuk pesan error.
     */
    public function attributes(): array
    {
        return [
            'nama'      => 'Nama Dosen',
            'nidn'      => 'NIDN',
            'role_name' => 'Jabatan', // Sesuaikan dengan nama input di form Anda
        ];
    }
}