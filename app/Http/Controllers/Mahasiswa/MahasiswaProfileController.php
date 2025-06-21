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
        $user = User::find(Auth::id()); // atau Auth::user();
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
        $user = User::find(Auth::id()); // atau Auth::user();
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

        return redirect()->route('user.profile.mhs')->with('success', 'Profil berhasil diperbarui.');
    }

    public function apiIndex()
    {
        $mahasiswa = Mahasiswa::with('user')->get();
        return response()->json($mahasiswa);
    }

    public function apiShow($id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
        return response()->json($mahasiswa);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'address' => 'nullable|string',
            'prodi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'address' => $request->address,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
        ]);

        return response()->json($mahasiswa, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $mahasiswa->user->id,
            'nim' => 'sometimes|required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'address' => 'nullable|string',
            'prodi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user
        if ($request->has('name') || $request->has('email') || $request->filled('password')) {
            $mahasiswa->user->update([
                'name' => $request->name ?? $mahasiswa->user->name,
                'email' => $request->email ?? $mahasiswa->user->email,
                'password' => $request->filled('password') ? bcrypt($request->password) : $mahasiswa->user->password,
            ]);
        }

        // Update mahasiswa
        $mahasiswa->update([
            'nim' => $request->nim ?? $mahasiswa->nim,
            'address' => $request->address ?? $mahasiswa->address,
            'prodi' => $request->prodi ?? $mahasiswa->prodi,
            'angkatan' => $request->angkatan ?? $mahasiswa->angkatan,
        ]);

        return response()->json($mahasiswa);
    }

    public function apiDestroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        // Delete related user first
        $user = $mahasiswa->user;
        $mahasiswa->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json(['message' => 'Mahasiswa deleted successfully']);
    }
}
