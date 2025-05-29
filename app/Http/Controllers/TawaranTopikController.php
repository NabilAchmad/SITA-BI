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

        return back()->with('success', 'Tawaran topik berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menyimpan tawaran topik: ' . $e->getMessage());
    }
}


    public function read(Request $request)
    {
        // Misalnya filter berdasarkan kuota (jika ada)
        $query = TawaranTopik::orderBy('created_at', 'desc');

        if ($request->has('min_kuota')) {
            $query->where('kuota', '>=', $request->input('min_kuota'));
        }

        $TawaranTopik = $query->paginate(10)->appends($request->query());

        return view('admin.TawaranTopik.crud-TawaranTopik.read', compact('TawaranTopik'));
    }

    public function update(Request $request, $id)
    {
        $TawaranTopik = TawaranTopik::findOrFail($id);

        $request->validate([
            'judul_topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kuota' => 'required|integer|min:1',
        ]);

        $TawaranTopik->update([
            'judul_topik' => $request->judul_topik,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
        ]);

        return redirect()->route('tawaran.read')->with('success', 'Tawaran topik berhasil diperbarui.');
    }

    public function edit($id)
    {
        $TawaranTopik = TawaranTopik::findOrFail($id);
        return view('admin.TawaranTopik.crud-TawaranTopik.edit', compact('TawaranTopik'));
    }

    public function destroy($id)
    {
        $TawaranTopik = TawaranTopik::findOrFail($id);
        $TawaranTopik->delete();

        return response()->json([
            'message' => 'Tawaran topik berhasil dihapus sementara.'
        ]);
    }

    public function trashed()
    {
        $tawaran = TawaranTopik::onlyTrashed()->paginate(10);
        return view('admin.TawaranTopik.crud-TawaranTopik.trashed', compact('TawaranTopik'));
    }

    public function restore($id)
    {
        $TawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $TawaranTopik->restore();

        return redirect()->back()->with('success', 'Tawaran topik berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $TawaranTopik = TawaranTopik::onlyTrashed()->findOrFail($id);
        $TawaranTopik->forceDelete();

        return redirect()->back()->with('success', 'Tawaran topik dihapus permanen.');
    }

    public function forceDeleteAll()
    {
        TawaranTopik::onlyTrashed()->forceDelete();

        return redirect()->back()->with('success', 'Semua tawaran topik terhapus telah dihapus permanen.');
    }
}
