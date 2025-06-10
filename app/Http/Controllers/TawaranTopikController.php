<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TawaranTopik;

class TawaranTopikController extends Controller
{
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
                'user_id' => Auth::id() ?? 1,
            ]);

            return redirect()->route('tawaran.read')->with('success', 'Tawaran topik berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan tawaran topik: ' . $e->getMessage());
        }
    }

    public function read(Request $request)
    {
        // Ambil semua tawaran topik terbaru (tanpa filter user)
        $tawaranTopik = TawaranTopik::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.TawaranTopik.crud-TawaranTopik.read', compact('tawaranTopik'));
    }

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

        return redirect()->route('tawaran.read')->with('success', 'Tawaran topik berhasil diperbarui.');
    }

    public function edit($id)
    {
        $tawaranTopik = TawaranTopik::findOrFail($id);
        return view('admin.TawaranTopik.crud-TawaranTopik.edit', compact('tawaranTopik'));
    }

    public function destroy($id)
    {
        $tawaranTopik = TawaranTopik::findOrFail($id);
        $tawaranTopik->delete();

        return response()->json([
            'message' => 'Tawaran topik berhasil dihapus sementara.'
        ]);
    }

    public function trashed()
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->paginate(10);
        return view('admin.TawaranTopik.crud-TawaranTopik.trashed', compact('tawaranTopik'));
    }

    public function restore($id)
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $tawaranTopik->restore();

        return redirect()->back()->with('success', 'Tawaran topik berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $tawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $tawaranTopik->forceDelete();

        return redirect()->back()->with('success', 'Tawaran topik dihapus permanen.');
    }

    public function forceDeleteAll()
    {
        TawaranTopik::onlyTrashed()->forceDelete();

        return redirect()->back()->with('success', 'Semua tawaran topik terhapus telah dihapus permanen.');
    }
}