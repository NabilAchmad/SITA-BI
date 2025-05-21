<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\Sidang;

class LaporanController extends Controller
{
    public function show()
    {
        $mahasiswaPerProdi = Mahasiswa::select('prodi', DB::raw('count(*) as total'))
            ->groupBy('prodi')
            ->get();

        $sidangPerStatus = Sidang::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('admin.laporan.views.lihatLaporanStatistik', compact('mahasiswaPerProdi', 'sidangPerStatus'));
    }
}
