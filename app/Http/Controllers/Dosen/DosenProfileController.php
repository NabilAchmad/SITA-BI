<?php

// --- File: app/Http/Controllers/Dosen/DosenProfileController.php ---
// ✅ PERBAIKAN: Controller sekarang sangat ramping dan bersih.

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Services\Dosen\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DosenProfileController extends Controller
{
    // Gunakan constructor property promotion untuk service yang lebih ringkas.
    public function __construct(protected DashboardService $service) {}

    /**
     * Menampilkan halaman dashboard untuk semua jenis dosen.
     */
    public function index_dosen()
    {
        // 1. Ambil pengguna yang sedang login.
        $user = Auth::user();

        // 2. Panggil SATU method dari service untuk menyiapkan SEMUA data.
        $dashboardData = $this->service->getDataForDashboard($user);

        // 3. Kirim data yang sudah siap pakai ke view.
        return view('dosen.views.dashboard', $dashboardData);
    }

    public function profile()
    {
        return view('dosen.user.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        // Sebaiknya pindahkan validasi ini ke dalam Form Request terpisah.
        $user = User::find(Auth::id());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('avatar')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
