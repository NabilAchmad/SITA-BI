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
        // ✅ OTORISASI TEPAT: Menggunakan permission dari Spatie sebagai lapisan keamanan.
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

            // ✅ VALIDASI UNIQUE YANG BENAR: Aturan unique diberi tahu untuk mengabaikan user ID saat ini.
            // Ini adalah cara yang benar untuk proses update.
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],

            'password'  => ['nullable', 'string', 'min:8', 'confirmed'], // Password boleh kosong saat update

            // ✅ VALIDASI UNIQUE YANG BENAR: Aturan unique diberi tahu untuk mengabaikan NIDN dosen saat ini.
            'nidn'      => ['required', 'string', 'max:50', Rule::unique('dosen')->ignore($dosen->id)],

            // Validasi nama role, memastikan hanya jabatan yang valid yang bisa dipilih.
            'role_name' => [
                'nullable',
                'string',
                Rule::exists('roles', 'name')->whereIn('name', [
                    'kajur',
                    'kaprodi-d3',
                    'kaprodi-d4',
                ])
            ],
        ];
    }

    /**
     * Sesuaikan nama atribut untuk pesan error agar lebih mudah dibaca.
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
