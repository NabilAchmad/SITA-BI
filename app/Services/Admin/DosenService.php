<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DosenService
{
    public function getDosenWithFilters(Request $request): LengthAwarePaginator
    {
        $query = Dosen::query()->with(['user.roles']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest()->paginate(10);
    }

    public function createDosen(array $validatedData): Dosen
    {
        return DB::transaction(function () use ($validatedData) {
            // 1. Buat User
            $user = User::create([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // 2. Siapkan dan berikan Roles
            $roleIds = [5]; // ID 5 untuk role 'dosen' adalah wajib
            if (!empty($validatedData['role_id'])) {
                $roleIds[] = $validatedData['role_id'];
            }
            $user->roles()->attach(array_unique($roleIds));

            // 3. Buat Dosen
            return $user->dosen()->create([
                'nidn' => $validatedData['nidn'],
            ]);
        });
    }

    public function updateDosen(Dosen $dosen, array $validatedData): bool
    {
        return DB::transaction(function () use ($dosen, $validatedData) {
            // 1. Update User
            $userData = [
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'],
            ];
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }
            $dosen->user->update($userData);

            // 2. Sinkronkan Roles
            $roleIds = [5]; // ID 5 untuk role 'dosen' adalah wajib
            if (!empty($validatedData['role_id'])) {
                $roleIds[] = $validatedData['role_id'];
            }
            $dosen->user->roles()->sync(array_unique($roleIds));

            // 3. Update Dosen
            return $dosen->update(['nidn' => $validatedData['nidn']]);
        });
    }

    public function deleteDosen(Dosen $dosen): bool
    {
        return DB::transaction(function () use ($dosen) {
            $user = $dosen->user;
            $user->roles()->detach();
            $dosen->delete();
            return $user->delete();
        });
    }
}
