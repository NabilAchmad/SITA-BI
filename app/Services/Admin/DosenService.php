<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DosenService
{
    /**
     * Mengambil data dosen dengan filter dan paginasi.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getDosenWithFilters(Request $request): LengthAwarePaginator
    {
        $query = Dosen::query()->with(['user.roles']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest('id')->paginate(10);
    }

    /**
     * Membuat data dosen baru beserta user dan rolenya.
     * ✅ SUDAH DIPERBAIKI: Menggunakan nama role, bukan ID.
     *
     * @param array $validatedData Data dari StoreDosenRequest
     * @return Dosen
     */
    public function createDosen(array $validatedData): Dosen
    {
        return DB::transaction(function () use ($validatedData) {
            // 1. Buat data User
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // 2. Siapkan dan berikan Roles berdasarkan NAMA
            $rolesToAssign = ['dosen']; // Setiap dosen pasti punya role 'dosen'
            if (!empty($validatedData['role_name'])) {
                // Tambahkan role jabatan jika ada (misal: 'kajur', 'kaprodi-d3')
                $rolesToAssign[] = $validatedData['role_name'];
            }
            // Gunakan metode assignRole() dari Spatie yang bisa menerima array nama role
            $user->assignRole(array_unique($rolesToAssign));

            // 3. Buat data Dosen yang berelasi dengan User
            return $user->dosen()->create([
                'nidn' => $validatedData['nidn'],
            ]);
        });
    }

    /**
     * Memperbarui data dosen, user, dan rolenya.
     * ✅ SUDAH DIPERBAIKI: Menggunakan nama role, bukan ID.
     *
     * @param Dosen $dosen
     * @param array $validatedData Data dari UpdateDosenRequest
     * @return bool
     */
    public function updateDosen(Dosen $dosen, array $validatedData): bool
    {
        return DB::transaction(function () use ($dosen, $validatedData) {
            // 1. Update data pada tabel User
            $userData = [
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
            ];
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }
            $dosen->user->update($userData);

            // 2. Sinkronkan Roles berdasarkan NAMA
            $rolesToSync = ['dosen']; // Role 'dosen' adalah wajib
            if (!empty($validatedData['role_name'])) {
                // Tambahkan role jabatan jika ada
                $rolesToSync[] = $validatedData['role_name'];
            }
            // Gunakan syncRoles() dari Spatie, yang akan menghapus role lama dan menerapkan yang baru
            $dosen->user->syncRoles(array_unique($rolesToSync));

            // 3. Update data pada tabel Dosen
            return $dosen->update(['nidn' => $validatedData['nidn']]);
        });
    }

    /**
     * Menghapus data dosen beserta user dan relasi rolenya.
     *
     * @param Dosen $dosen
     * @return bool
     */
    public function deleteDosen(Dosen $dosen): bool
    {
        return DB::transaction(function () use ($dosen) {
            $user = $dosen->user;

            // Hapus relasi role dan user
            $user->roles()->detach();
            $dosen->delete();

            return $user->delete();
        });
    }
}
