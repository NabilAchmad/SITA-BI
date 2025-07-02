<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\PeranDosenTA;
use App\Models\Log;
use App\Models\User;
use App\Models\TawaranTopik;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\Dosen\DashboardService;
use Illuminate\Support\Facades\Auth;

class DosenProfileController extends Controller
{


    public function index_dosen(DashboardService $service)
    {
        $pengumumans = Pengumuman::with('pembuat')->latest()->get();
        $riwayatTA = TugasAkhir::latest()->get();

        $tawaranTopik = TawaranTopik::with(['tugasAkhir.mahasiswa.user'])
            ->where('user_id', Auth::id())->get();

        $card1 = $service->card1Data();
        $card2 = $service->card2Data();
        $card3 = $service->card3Data();
        $card4 = $service->card4Data();
        $peranDosen = $service->getPeranDosen();
        $role = Auth::user()->dosen->user->roles;
        $jadwalBimbingan = $service->jadwalBimbinganTerdekat();
        $jadwalSidang = $service->jadwalSidangTerdekat();

        return view('dosen.views.dashboard', compact(
            'pengumumans',
            'riwayatTA',
            'tawaranTopik',
            'card1',
            'card2',
            'card3',
            'card4',
            'peranDosen',
            'role',
            'jadwalBimbingan',
            'jadwalSidang'
        ));
    }

    public function profile()
    {
        $user = User::find(Auth::id());
        return view('dosen.user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

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
}
