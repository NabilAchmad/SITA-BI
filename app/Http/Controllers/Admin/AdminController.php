<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Services\Admin\DashboardService;
use App\Services\Admin\ProfileService;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama Admin.
     */
    public function index(DashboardService $dashboardService)
    {
        // Cukup panggil service untuk mendapatkan semua data
        $dashboardData = $dashboardService->getDashboardData();

        return view('admin.views.dashboard', $dashboardData);
    }

    /**
     * Menampilkan halaman profil Admin.
     */
    public function profile()
    {
        return view('admin.user.views.profile', ['user' => Auth::user()]);
    }

    /**
     * Memperbarui profil Admin.
     */
    public function update(UpdateProfileRequest $request, ProfileService $profileService)
    {
        $profileService->updateUserProfile(
            Auth::user(),
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
