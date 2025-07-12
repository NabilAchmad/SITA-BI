<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class MahasiswaProfileController extends Controller
{
    /**
     * Menampilkan halaman profil mahasiswa yang sedang login.
     * Menggunakan konvensi 'show' untuk menampilkan satu resource.
     */
    public function show()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Cari data mahasiswa berdasarkan user_id.
        // Muat relasi 'user' secara bersamaan (Eager Loading) untuk efisiensi query.
        // firstOrFail() akan otomatis menampilkan halaman 404 jika data tidak ditemukan.
        $mahasiswa = Mahasiswa::where('user_id', $userId)->with('user')->firstOrFail();

        // Kirim data mahasiswa (yang sudah berisi relasi user) ke view.
        return view('mahasiswa.user.profile', ['mahasiswa' => $mahasiswa]);
    }

    /**
     * Mengupdate data profil mahasiswa.
     */
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        /** @var \App\Models\Mahasiswa $mahasiswa */
        $mahasiswa = $user->mahasiswa;

        // Jika data mahasiswa tidak ditemukan, kembalikan dengan pesan error.
        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Tentukan aturan validasi untuk kelas berdasarkan prodi yang diinput.
        $kelasRules = ['required', 'string', 'max:1'];
        if ($request->input('prodi') === 'D3') {
            $kelasRules[] = Rule::in(['a', 'b', 'c']);
        } else {
            $kelasRules[] = Rule::in(['a', 'b']);
        }

        // Validasi semua input dari form
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Diubah dari 'avatar' ke 'photo'
            'nim'     => ['required', 'string', 'max:20', Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id)],
            'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            'prodi'   => 'required|string|in:D3,D4',
            'kelas'   => $kelasRules,
        ]);

        try {
            // Gunakan transaksi database untuk memastikan integritas data.
            // Jika salah satu query gagal, semua perubahan akan dibatalkan (rollback).
            DB::transaction(function () use ($validatedData, $request, $user, $mahasiswa) {
                // 1. Update data di tabel 'users'
                $user->name = $validatedData['name'];
                $user->email = $validatedData['email'];

                // Cek jika ada file foto baru yang diunggah
                if ($request->hasFile('photo')) {
                    // Hapus foto lama jika ada
                    if ($user->photo && Storage::disk('public')->exists('avatars/' . $user->photo)) {
                        Storage::disk('public')->delete('avatars/' . $user->photo);
                    }
                    // Simpan foto baru dan dapatkan path-nya
                    $path = $request->file('photo')->store('avatars', 'public');
                    // Simpan hanya nama filenya saja, bukan path lengkap
                    $user->photo = basename($path);
                }
                $user->save();

                // 2. Update data di tabel 'mahasiswa'
                $mahasiswa->nim = $validatedData['nim'];
                $mahasiswa->angkatan = $validatedData['angkatan'];
                $mahasiswa->prodi = strtoupper($validatedData['prodi']);
                $mahasiswa->kelas = strtolower($validatedData['kelas']);
                $mahasiswa->save();
            });
        } catch (\Exception $e) {
            // Jika terjadi error selama transaksi, kembalikan dengan pesan error.
            return back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }

        // Redirect kembali ke halaman profil dengan pesan sukses.
        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
