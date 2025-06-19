<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\TugasAkhir;
use App\Models\PeranDosenTA;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.views.dashboard', [
            'totalDosen'      => Dosen::count(),
            'totalMahasiswa'  => Mahasiswa::count(),
            'totalPengumuman' => Pengumuman::count(),
            'riwayatTA'       => $this->getRiwayatTA(),
            'pengumumans'     => $this->getLatestPengumuman(),
            'logs'            => $this->getLatestLogs(),
            'dosenAktif'      => $this->getDosenAktif(),
            'totalPembimbing' => $this->getTotalPembimbing(),
            'totalPenguji'    => $this->getTotalPenguji(),
            'mahasiswaAktif'  => $this->getMahasiswaAktif()
        ]);
    }

    public function profile()
    {
        $user = User::findOrFail(21); // Ubah ID hardcoded bila perlu
        return view('admin.user.views.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(21); // Ubah ID hardcoded bila perlu

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->fill($validated);

        if ($request->hasFile('avatar')) {
            $this->updateAvatar($user, $request->file('avatar'));
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function logout()
    {
        // Implementasi logout jika diperlukan
    }

    // ===========================
    // Private helper methods
    // ===========================

    private function getRiwayatTA()
    {
        return TugasAkhir::latest()->get();
    }

    private function getLatestPengumuman()
    {
        return Pengumuman::with('pembuat')
            ->latest()
            ->get();
    }

    private function getLatestLogs()
    {
        return Log::with('user')
            ->latest()
            ->take(50)
            ->get();
    }

    private function getDosenAktif()
    {
        return Dosen::all()->filter(fn($dosen) => $dosen->isOnline());
    }

    private function getTotalPembimbing()
    {
        return PeranDosenTA::whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->distinct()
            ->count('dosen_id');
    }

    private function getTotalPenguji()
    {
        return PeranDosenTA::whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->distinct()
            ->count('dosen_id');
    }

    private function getMahasiswaAktif()
    {
        return Mahasiswa::count(); // Jika ada status "aktif", bisa disesuaikan logikanya
    }

    private function updateAvatar(User $user, $avatar)
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->photo = $avatar->store('avatars', 'public');
    }
}
