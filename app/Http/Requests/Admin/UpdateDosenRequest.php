<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $dosen = $this->route('dosen');
        $userId = $dosen->user_id;

        return [
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            'nidn' => ['required', 'string', 'max:50', Rule::unique('dosen')->ignore($dosen->id)],
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
