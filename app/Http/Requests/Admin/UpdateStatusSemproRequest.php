<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusSemproRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ganti dengan logika otorisasi admin Anda
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:lulus,lulus_revisi,tidak_lulus'
        ];
    }
}
