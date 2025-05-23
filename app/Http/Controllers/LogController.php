<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')->latest()->paginate(20);
        return view('admin.log.views.lihatLogAktifitas', compact('logs'));
    }
}
