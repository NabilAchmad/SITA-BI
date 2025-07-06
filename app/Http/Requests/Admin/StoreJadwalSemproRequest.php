<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalSemproRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ganti dengan logika otorisasi admin Anda, misal: return auth()->user()->isAdmin();
        return true;
    }

    public function rules(): array
    {
        return [
            'sidang_id' => 'required|exists:sidang,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'ruangan_id' => 'required|exists:ruangan,id',
            // tambahkan validasi untuk penguji jika perlu
            // 'penguji_1_id' => 'required|exists:dosen,id',
        ];
    }
}
