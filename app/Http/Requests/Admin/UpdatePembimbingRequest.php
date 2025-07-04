<?php

namespace App\Http\Requests\Admin;

use App\Models\TugasAkhir;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- PENTING: Tambahkan ini

class UpdatePembimbingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var \App\Models\TugasAkhir|null $tugasAkhir */
        // Ambil TugasAkhir dari route. Ini harus berhasil jika Langkah 1 benar.
        $tugasAkhir = $this->route('tugasAkhir');

        // Failsafe: Jika karena alasan apa pun tugasAkhir tidak ditemukan, gagalkan.
        if (!$tugasAkhir) {
            // Ini seharusnya tidak pernah terjadi jika route Anda benar.
            // Jika terjadi, ini menandakan masalah pada route atau controller binding.
            abort(404, 'Data Tugas Akhir tidak ditemukan.');
        }

        // Cek nama tabel dosen Anda. Ganti 'dosens' jika nama tabel Anda 'dosen'.
        $dosenTableName = 'dosen'; // <-- GANTI INI JIKA PERLU

        // Cek apakah tugas akhir ini berasal dari alur tawaran topik
        $isFromTawaranTopik = !is_null($tugasAkhir->tawaran_topik_id);

        // Jika dari TAWABAN TOPIK, Pembimbing 1 terkunci.
        // Fokus validasi hanya pada Pembimbing 2.
        if ($isFromTawaranTopik) {
            return [
                'pembimbing1' => [
                    'required',
                    Rule::exists($dosenTableName, 'id'),
                    Rule::in([$tugasAkhir->peranDosenTA->where('peran', 'pembimbing1')->first()?->dosen_id])
                ],
                'pembimbing2' => [
                    'required',
                    Rule::exists($dosenTableName, 'id'),
                    'different:pembimbing1'
                ],
            ];
        }

        // Jika dari alur NORMAL, pengguna bisa mengubah keduanya.
        // Validasi keduanya dengan ketat.
        return [
            'pembimbing1' => ['required', Rule::exists($dosenTableName, 'id')],
            'pembimbing2' => [
                'required',
                Rule::exists($dosenTableName, 'id'),
                'different:pembimbing1'
            ],
        ];
    }
}
