<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenService
{
    /**
     * Mengambil daftar dosen dengan paginasi dan filter.
     */
    public function getDosenWithFilters(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Dosen::query()->with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('nidn', 'like', '%' . $request->search . '%');
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Membuat data User dan Dosen baru dalam satu transaksi.
     */
    public function createDosen(array $validatedData): Dosen
    {
        return DB::transaction(function () use ($validatedData) {
            // 1. Buat data User
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // 2. Berikan peran 'dosen'
            $dosenRole = Role::where('nama_role', 'dosen')->firstOrFail();
            $user->roles()->attach($dosenRole);

            // 3. Buat data Dosen yang terhubung dengan User
            $dosen = $user->dosen()->create([
                'nidn' => $validatedData['nidn'],
                'jabatan' => $validatedData['jabatan'] ?? null,
            ]);

            return $dosen;
        });
    }

    /**
     * Memperbarui data User dan Dosen.
     */
    public function updateDosen(Dosen $dosen, array $validatedData): bool
    {
        return DB::transaction(function () use ($dosen, $validatedData) {
            // 1. Update data di tabel user
            $dosen->user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Jika ada password baru, update password
            if (!empty($validatedData['password'])) {
                $dosen->user->update([
                    'password' => Hash::make($validatedData['password']),
                ]);
            }

            // 2. Update data di tabel dosen
            return $dosen->update([
                'nidn' => $validatedData['nidn'],
                'jabatan' => $validatedData['jabatan'] ?? null,
            ]);
        });
    }

    /**
     * Menghapus data User dan Dosen.
     */
    public function deleteDosen(Dosen $dosen): bool
    {
        return DB::transaction(function () use ($dosen) {
            $user = $dosen->user;
            $dosen->delete();
            $user->delete();
            return true;
        });
    }
}
