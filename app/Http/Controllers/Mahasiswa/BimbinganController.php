<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\BimbinganService;

class BimbinganController extends Controller
{
    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    public function dashboard()
    {
        return $this->bimbinganService->dashboard();
    }

    public function ajukanJadwal()
    {
        return $this->bimbinganService->ajukanJadwal();
    }

    public function store(Request $request)
    {
        return $this->bimbinganService->store($request);
    }

    public function ubahJadwal(Request $request, $id)
    {
        return $this->bimbinganService->ubahJadwal($request, $id);
    }
}
