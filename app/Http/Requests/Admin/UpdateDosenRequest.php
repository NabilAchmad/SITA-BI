<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Penting: Jangan lupa import Rule

class UpdateDosenRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        // Menggunakan permission untuk otorisasi
        return $this->user()->can('manage user accounts');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        // Dapatkan objek Dosen dari route model binding
        $dosen = $this->route('dosen');

        // Dapatkan ID user yang berelasi dengan dosen ini
        $userId = $dosen->user->id;

        return [
            'nama'      => ['required', 'string', 'max:255'],

            // ✅ PERBAIKAN: Aturan unique diberi tahu untuk mengabaikan user ID saat ini
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],

            'password'  => ['nullable', 'string', 'min:8', 'confirmed'], // Password boleh kosong saat update

            // ✅ PERBAIKAN: Aturan unique diberi tahu untuk mengabaikan NIDN dosen saat ini
            'nidn'      => ['required', 'string', 'max:50', Rule::unique('dosen')->ignore($dosen->id)],

            // Validasi nama role
            'role_name' => [
                'nullable',
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
            'role_name' => 'Jabatan',
        ];
    }
}
