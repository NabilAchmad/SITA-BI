<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Sidang;

class StorePenilaianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Method ini akan berjalan sebelum validasi. Jika mengembalikan false,
     * Laravel akan otomatis menampilkan halaman 403 Akses Ditolak.
     */
    public function authorize(): bool
    {
        /** @var Sidang $sidang */
        // Mengambil objek 'sidang' dari parameter route
        $sidang = $this->route('sidang');
        if (!$sidang) {
            return false;
        }
        $dosen = $this->user()->dosen;

        // Otorisasi: Cek apakah dosen yang login adalah salah satu penguji untuk sidang ini
        return $sidang->tugasAkhir->peranDosenTa()
            ->where('dosen_id', $dosen->id)
            ->where('peran', 'like', 'penguji%')
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // PERBAIKAN: Menyesuaikan aturan validasi dengan skema tabel 'nilai_sidang'
        // yang menggunakan format 'long' (aspek, skor, komentar per baris).
        // Kita asumsikan form mengirim data dalam bentuk array 'penilaian'.
        return [
            // Memastikan 'penilaian' adalah array dan wajib diisi.
            'penilaian' => 'required|array',
            // Validasi untuk setiap item di dalam array 'penilaian'.
            // 'penilaian.*.aspek' berarti 'aspek' di setiap item array 'penilaian'
            'penilaian.*.aspek' => 'required|string|max:100',
            'penilaian.*.skor' => 'required|numeric|min:0|max:100',
            'penilaian.*.komentar' => 'nullable|string|max:1000',
        ];
    }
}
