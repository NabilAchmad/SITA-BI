<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;
use App\Http\Controllers\Controller;

class PengumumanController extends Controller
{
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

            return back()->with('success', 'Pengumuman berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pengumuman.');
        }
    }

    public function read(Request $request)
    {
        $query = Pengumuman::orderBy('tanggal_dibuat', 'desc');

        $audiens = $request->input('audiens');

        $validAudiens = ['registered_users', 'dosen', 'mahasiswa', 'guest', 'all_users'];

        if (in_array($audiens, $validAudiens)) {
            if ($audiens === 'registered_users') {
                // Tampilkan hanya dosen dan mahasiswa (tanpa all_users)
                $query->whereIn('audiens', ['dosen', 'mahasiswa']);
            } elseif ($audiens === 'all_users') {
                // Tampilkan semua tanpa filter
            } else {
                // Untuk dosen, mahasiswa, guest tampilkan audiens yang dipilih plus all_users
                $query->where(function ($q) use ($audiens) {
                    $q->where('audiens', $audiens)
                        ->orWhere('audiens', 'all_users');
                });
            }
        }
        // Jika audiens tidak valid atau kosong, tampilkan semua data tanpa filter

        $pengumuman = $query->paginate(10)->appends($request->query());

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
}
