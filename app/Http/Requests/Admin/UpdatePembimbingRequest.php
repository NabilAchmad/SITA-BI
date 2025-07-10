<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePembimbingRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        // ✅ OTORISASI SUDAH BENAR:
        // Otorisasi dasar ditangani di sini, sementara otorisasi yang lebih spesifik
        // (misalnya, hanya kaprodi yang bisa update) ditangani oleh middleware di route.
        return $this->user()->hasAnyRole(['admin', 'kajur', 'kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        /** @var \App\Models\TugasAkhir|null $tugasAkhir */
        $tugasAkhir = $this->route('tugasAkhir');
        if (!$tugasAkhir) {
            abort(404, 'Data Tugas Akhir tidak ditemukan.');
        }

        $isFromTawaranTopik = !is_null($tugasAkhir->tawaran_topik_id);

        // Ambil ID pembimbing 1 yang sudah ada (jika ada)
        $existingPembimbing1Id = $tugasAkhir->pembimbingSatu?->dosen_id;

        // Siapkan aturan dasar untuk Pembimbing 1
        $pembimbing1Rules = ['required', 'exists:dosen,id'];

        // Jika berasal dari Tawaran Topik DAN pembimbing 1 sudah ada, kunci nilainya.
        if ($isFromTawaranTopik && $existingPembimbing1Id) {
            $pembimbing1Rules[] = Rule::in([$existingPembimbing1Id]);
        }

        return [
            // Gunakan variabel yang sudah berisi aturan yang benar.
            'pembimbing_1_id' => $pembimbing1Rules,

            'pembimbing_2_id' => [
                // Jika dari tawaran topik, pembimbing 2 wajib diisi. Jika tidak, boleh kosong.
                $isFromTawaranTopik ? 'required' : 'nullable',
                'exists:dosen,id',

                /**
                 * ✅ PERBAIKAN KRITIS: Kesalahan ketik 'diferent' diubah menjadi 'different'.
                 * Aturan ini memastikan Pembimbing 2 tidak boleh sama dengan Pembimbing 1.
                 */
                'different:pembimbing_1_id'
            ],
        ];
    }

    /**
     * Menambahkan pesan error kustom agar lebih mudah dipahami.
     */
    public function messages(): array
    {
        return [
            'pembimbing_1_id.in' => 'Pembimbing 1 tidak dapat diubah karena berasal dari tawaran topik.',
            'pembimbing_2_id.different' => 'Pembimbing 2 tidak boleh sama dengan Pembimbing 1.',
        ];
    }
}
