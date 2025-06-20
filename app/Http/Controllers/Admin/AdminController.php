<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\PeranDosenTA;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function index()
    {
        $totalDosen = Dosen::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalPengumuman = Pengumuman::count();
        $riwayatTA = TugasAkhir::latest()->get();
        $pengumumans = Pengumuman::with('pembuat')->orderBy('created_at', 'desc')->get();
        $logs = Log::with('user') // Jika ada relasi ke user
            ->latest()
            ->take(50)
            ->get();

        // Dosen yang sedang online (menggunakan cache-based online detection)
        $dosenAktif = Dosen::all()->filter(fn($d) => $d->isOnline());

        // Dosen Pembimbing (distinct dosen_id dari peran_dosen_ta dengan peran seperti pembimbing1, pembimbing2)
        $totalPembimbing = PeranDosenTA::whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->distinct('dosen_id')
            ->count('dosen_id');

        // Dosen Penguji (distinct dosen_id dari peran_dosen_ta dengan peran penguji1–penguji4)
        $totalPenguji = PeranDosenTA::whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->distinct('dosen_id')
            ->count('dosen_id');

        // Mahasiswa aktif = yang punya tugas akhir dan belum dibatalkan
        $mahasiswaAktif = Mahasiswa::count();

        return view('admin.views.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalPengumuman',
            'riwayatTA',
            'pengumumans',
            'dosenAktif',
            'totalPembimbing',
            'totalPenguji',
            'mahasiswaAktif',
            'logs'
        ));
    }

    public function profile()
    {
        $user = \App\Models\User::findOrFail(1);
        // dd($user);
        return view('admin.user.views.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(1);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $user->photo = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function logout() {}
}
