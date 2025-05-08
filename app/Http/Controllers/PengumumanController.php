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

    public function read(Request $request)
    {
        $pengumuman = Pengumuman::orderBy('tanggal_dibuat', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.pengumuman.views.readPengumuman', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'audiens' => 'required|in:guest,registered_users,all_users,dosen,mahasiswa',
        ]);

        // Update pengumuman
        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'audiens' => $request->audiens,
            // Tidak perlu mengupdate 'dibuat_oleh' karena itu tidak berubah
            'dibuat_oleh' => $pengumuman->dibuat_oleh,  // Tetap menggunakan user yang pertama membuat
            // Jangan update tanggal_dibuat, biarkan tetap dari DB
        ]);

        // Redirect dengan pesan sukses setelah update
        return redirect()->route('pengumuman.read')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.views.editPengumuman', compact('pengumuman'));
    }

    // force dan soft delete
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return response()->json([
            'message' => 'Pengumuman berhasil dihapus sementara.'
        ]);
    }

    public function trashed()
    {
        $pengumuman = Pengumuman::onlyTrashed()->paginate(10);
        return view('admin.pengumuman.views.trashedPengumuman', compact('pengumuman'));
    }

    public function restore($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->restore();
        return redirect()->back()->with('success', 'Pengumuman berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->forceDelete();
        return redirect()->back()->with('success', 'Pengumuman dihapus permanen.');
    }

    public function forceDeleteAll()
    {
        Pengumuman::onlyTrashed()->forceDelete();

        return redirect()->back()->with('success', 'Semua pengumuman terhapus telah dihapus permanen.');
    }

    public function tampil()
    {
        $pengumumans = Pengumuman::with('pembuat')->orderBy('created_at', 'desc')->get();

        return view('admin.views.dashboard', compact('pengumumans'));
    }
}
