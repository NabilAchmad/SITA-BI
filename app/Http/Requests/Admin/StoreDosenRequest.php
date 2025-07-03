<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'nidn' => 'required|string|max:50|unique:dosen,nidn',
            // Validasi untuk role_id, hanya menerima ID 2, 3, atau 4
            'role_id' => 'nullable|integer|in:2,3,4',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama Dosen',
            'nidn' => 'NIDN',
            'role_id' => 'Jabatan',
        ];
    }
}
