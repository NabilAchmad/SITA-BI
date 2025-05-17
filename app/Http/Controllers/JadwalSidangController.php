<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;

class JadwalSidangController extends Controller
{

    public function index()
    {
        // JadwalSidangController@index misalnya
        $jadwalList = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->get();



        return view('admin.sidang.jadwal.views.readJadwalSidang', compact('jadwalList'));
    }

    public function edit($id)
    {
        $jadwal = JadwalSidang::with(['sidang.tugasAkhir.mahasiswa.user', 'ruangan'])->findOrFail($id);
        $ruanganList = Ruangan::all();

        return view('admin.sidang.edit-jadwal', compact('jadwal', 'ruanganList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'ruangan_id' => 'required|exists:ruangan,id',
        ]);

        $jadwal = JadwalSidang::findOrFail($id);
        $jadwal->update([
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'ruangan_id' => $request->ruangan_id,
        ]);

        return redirect()->route('jadwal-sidang.read')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalSidang::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal-sidang.read')->with('success', 'Jadwal berhasil dihapus.');
    }
}
