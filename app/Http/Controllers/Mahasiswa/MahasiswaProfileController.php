<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Http\Controllers\Controller;

class MahasiswaProfileController extends Controller
{
    /**
     * Tampilkan halaman profil mahasiswa
     */
    public function profile()
    {
        $user = User::findOrFail(2); // atau Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Ambil prodi unik dari tabel mahasiswa (misalnya D3, D4)
        $daftarProdi = Mahasiswa::select('prodi')->distinct()->orderBy('prodi')->pluck('prodi');

        return view('mahasiswa.user.profile', compact('user', 'mahasiswa', 'daftarProdi'));
    }

    /**
     * Update data profil mahasiswa
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(2); // atau Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id),
            ],
            'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            'prodi' => 'required|in:D3,D4',
            'kelas' => 'required|in:a,b,c',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        $mahasiswa->nim = $request->nim;
        $mahasiswa->angkatan = $request->angkatan;
        $mahasiswa->prodi = strtoupper($request->prodi);
        $mahasiswa->kelas = strtolower($request->kelas); // ← disimpan dalam huruf kecil
        $mahasiswa->save();
        $mahasiswa->refresh(); // ← Tambahkan ini

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
