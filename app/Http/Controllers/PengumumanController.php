<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{

    public function create()
    {
        return view('admin.pengumuman.views.createPengumuman');  // Menampilkan form
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'audiens' => 'required|in:guest,registered_users,all_users,dosen,mahasiswa',
        ]);

        try {
            Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'audiens' => $request->audiens,
                'dibuat_oleh' => Auth::id() ?? 1, // asumsinya user sedang login
                'tanggal_dibuat' => now(),   // bisa juga default dari DB
            ]);

            return back()->with('success', true);
        } catch (\Exception $e) {
            return back()->with('error', true);
        }
    }
}
