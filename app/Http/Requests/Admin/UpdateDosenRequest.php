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
        $dosenId = $dosen->id;
        $userId = $dosen->user_id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
            'nidn' => ['required', 'string', 'max:20', Rule::unique('dosen')->ignore($dosenId)],
            'jabatan' => 'nullable|string|max:100',
        ];
    }
}
