<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Memperbarui profil user, termasuk foto.
     */
    public function updateUserProfile(User $user, array $validatedData, ?UploadedFile $avatarFile): User
    {
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        if ($avatarFile) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            // Simpan foto baru
            $user->photo = $avatarFile->store('avatars', 'public');
        }

        $user->save();
        return $user;
    }
}
