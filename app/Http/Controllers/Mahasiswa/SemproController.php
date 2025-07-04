<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemproController extends Controller
{
    public function dashboard(){
        return view('mahasiswa.sempro.dashboard');
    }
}
