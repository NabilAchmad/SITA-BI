<?php

namespace App\Http\Controllers\Dosen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TawaranTopik;
use App\Http\Controllers\Controller;
use App\Models\User;

class TawaranTopikController extends Controller
{
    // CREATE (store data)
    public function store(Request $request)
    {
        $request->validate([
            'judul_topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kuota' => 'required|integer|min:1',
        ]);

        try {
            TawaranTopik::create([
                'judul_topik' => $request->judul_topik,
                'deskripsi' => $request->deskripsi,
                'kuota' => $request->kuota,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('dosen.tawaran-topik.index')->with('success', 'Tawaran topik berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan tawaran topik: ' . $e->getMessage());
        }
    }

    // READ (menampilkan list dengan fitur search)
    public function read(Request $request)
    {
        $query = TawaranTopik::query();

        // Fitur search berdasarkan judul atau deskripsi
        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_topik', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $tawaranTopik = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('dosen.tawaran-topik.crud-TawaranTopik.read', compact('tawaranTopik'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $tawaranTopik = TawaranTopik::findOrFail($id);

        $request->validate([
            'judul_topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kuota' => 'required|integer|min:1',
        ]);

        $tawaranTopik->update([
            'judul_topik' => $request->judul_topik,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
        ]);

        return redirect()->route('dosen.tawaran-topik.index')->with('success', 'Tawaran topik berhasil diperbarui.');
    }

    // EDIT (tampilkan form edit)
    public function edit($id)
    {
        $tawaranTopik = TawaranTopik::findOrFail($id);
        return view('admin.TawaranTopik.crud-TawaranTopik.edit', compact('tawaranTopik'));
    }

    // DELETE (soft delete)
    public function destroy($id)
    {
        $tawaranTopik = TawaranTopik::findOrFail($id);
        $tawaranTopik->delete();

        return response()->json(['message' => 'Tawaran topik berhasil dihapus sementara.']);
    }

    // Tampilkan data yang sudah terhapus (trash)
    public function trashed()
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->paginate(10);
        return view('admin.TawaranTopik.crud-TawaranTopik.trashed', compact('tawaranTopik'));
    }

    // Pulihkan soft-deleted
    public function restore($id)
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $tawaranTopik->restore();

        return redirect()->back()->with('success', 'Tawaran topik berhasil dipulihkan.');
    }

    // Hapus permanen satu
    public function forceDelete($id)
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $tawaranTopik->forceDelete();

        return redirect()->back()->with('success', 'Tawaran topik dihapus permanen.');
    }

    // Hapus permanen semua
    public function forceDeleteAll()
    {
        TawaranTopik::onlyTrashed()->forceDelete();

        return redirect()->back()->with('success', 'Semua tawaran topik terhapus telah dihapus permanen.');
    }
}
