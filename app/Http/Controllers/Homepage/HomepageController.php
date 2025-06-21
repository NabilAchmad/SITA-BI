<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::whereIn('audiens', ['all_users', 'guest'])
            ->orderByDesc('tanggal_dibuat')
            ->take(10)
            ->get();

        return view('home.homepage', compact('pengumuman'));
    }
}
