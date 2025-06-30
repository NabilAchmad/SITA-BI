<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTawaranTopikRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Mengambil objek 'tawaranTopik' dari parameter route
        $tawaranTopik = $this->route('tawaranTopik');

        // PERBAIKAN: Otorisasi berdasarkan 'user_id' untuk memastikan
        // dosen yang login adalah pemilik topik ini.
        return $tawaranTopik && $tawaranTopik->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // PERBAIKAN: Menyesuaikan nama field dan aturan dengan skema DB
            'judul_topik' => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'kuota'       => 'required|integer|min:0',
        ];
    }
}
