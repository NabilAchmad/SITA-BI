<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    // Tampilkan mahasiswa yang sudah memiliki 2 pembimbing
    public function index()
    {
        $mahasiswa = Mahasiswa::whereHas('tugasAkhir.peranDosenTa', function ($q) {
            $q->whereIn('peran', ['pembimbing1', 'pembimbing2']);
        })->with([
            'user',
            'tugasAkhir.peranDosenTa.dosen.user'  // eager loading sampai nama dosen
        ])->get();

        return view('admin.mahasiswa.views.list-mhs', compact('mahasiswa'));
    }
}
