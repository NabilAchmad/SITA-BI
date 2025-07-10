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

            // 2. Siapkan dan berikan Roles
            $rolesToAssign = ['dosen'];
            if (!empty($validatedData['role_name'])) {
                $rolesToAssign[] = $validatedData['role_name'];
            }
            $user->assignRole(array_unique($rolesToAssign));

            /**
             * ✅ PERBAIKAN KRITIS: Hapus cache peran setelah peran baru diberikan.
             * Ini memastikan aplikasi akan membaca data peran yang baru saat
             * pengguna tersebut login atau saat datanya diakses lagi.
             */
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 3. Buat data Dosen yang berelasi
            return $user->dosen()->create([
                'nidn' => $validatedData['nidn'],
            ]);
        });
    }

    /**
     * Memperbarui data dosen, user, dan rolenya.
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

            // 2. Sinkronkan Roles
            $rolesToSync = ['dosen'];
            if (!empty($validatedData['role_name'])) {
                $rolesToSync[] = $validatedData['role_name'];
            }
            $dosen->user->syncRoles(array_unique($rolesToSync));

            /**
             * ✅ PERBAIKAN KRITIS: Hapus cache peran setelah peran diubah.
             * Ini sangat penting agar perubahan peran langsung terlihat di seluruh aplikasi.
             */
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 3. Update data pada tabel Dosen
            return $dosen->update(['nidn' => $validatedData['nidn']]);
        });
    }

    /**
     * Menghapus data dosen beserta user dan relasi rolenya.
     */
    public function deleteDosen(Dosen $dosen): bool
    {
        return DB::transaction(function () use ($dosen) {
            $user = $dosen->user;

            // Hapus relasi role dan user
            $user->roles()->detach();
            $dosen->delete();

            /**
             * ✅ PENINGKATAN: Hapus cache setelah menghapus user.
             */
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return $user->delete();
        });
    }
}
