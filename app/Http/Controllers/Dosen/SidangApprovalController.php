<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sidang;
use Illuminate\Support\Facades\Auth;

class SidangApprovalController extends Controller
{
    /**
     * Display a listing of mahasiswa sidang akhir registrations pending approval.
     */
    public function index()
    {
        $dosenId = Auth::user()->dosen->id;

        // Get sidang registrations where the dosen pembimbing is this dosen and status is 'menunggu'
        $sidangs = Sidang::where('status', 'menunggu')
            ->whereHas('tugasAkhir.dosenPembimbing', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->with(['tugasAkhir.mahasiswa.user'])
            ->get();

        return view('dosen.sidang.approvals.index', compact('sidangs'));
    }

    /**
     * Approve a mahasiswa sidang akhir registration.
     */
    public function approve($sidangId)
    {
        $sidang = Sidang::findOrFail($sidangId);

        // Check if the authenticated dosen is pembimbing of this sidang
        $dosenId = Auth::user()->dosen->id;
        $isPembimbing = $sidang->tugasAkhir->dosenPembimbing->contains('dosen_id', $dosenId);

        if (!$isPembimbing) {
            abort(403, 'Unauthorized action.');
        }

        // Update status to 'dijadwalkan' or approved status
        $sidang->status = 'dijadwalkan';
        $sidang->save();

        return redirect()->route('dosen.sidang.approvals.index')->with('success', 'Sidang approved successfully.');
    }
}
