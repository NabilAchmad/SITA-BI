<?php

namespace App\Services\Admin;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ WAJIB: Import DB facade
use Illuminate\Support\Facades\Hash; // ✅ LEBIH BAIK: Import Hash facade
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MahasiswaService
{
    /**
     * Mengambil daftar mahasiswa dengan filter dan pencarian.
     */
    public function getMahasiswaWithFilters(Request $request): LengthAwarePaginator
    {
        // ... (Tidak ada perubahan di sini, sudah bagus)
        $query = Mahasiswa::with('user')->orderBy('created_at', 'desc'); // Saran: Tambahkan urutan

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi); // Saran: Gunakan pencocokan persis, bukan LIKE
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate(10)->withQueryString(); // Saran: Gunakan withQueryString()
    }

    /**
     * ✅ PERBAIKAN: Memperbarui data menggunakan transaksi database yang aman.
     * Menerima objek Mahasiswa, bukan string id.
     */
    public function updateMahasiswa(array $validatedData, Mahasiswa $mahasiswa): Mahasiswa
    {
        // Bungkus semua operasi dalam satu transaksi
        DB::transaction(function () use ($validatedData, $mahasiswa) {

            // 1. Update data user terkait menggunakan mass assignment
            $userData = [
                'name'  => $validatedData['name'],
                'email' => $validatedData['email'],
            ];

            // Hanya update password jika diisi
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']); // Gunakan Hash::make
            }

            $mahasiswa->user()->update($userData);

            // 2. Update data mahasiswa menggunakan mass assignment
            $mahasiswa->update([
                'nim'   => $validatedData['nim'],
                'prodi' => $validatedData['prodi'],
            ]);
        }); // Jika ada error di dalam sini, semua akan dibatalkan

        return $mahasiswa;
    }
}
