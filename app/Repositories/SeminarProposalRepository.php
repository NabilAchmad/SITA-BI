<?php

namespace App\Repositories;

use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class SeminarProposalRepository
{
    protected $request;
    protected $perPage = 10;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Mengambil mahasiswa yang proposalnya berstatus 'draft' dan menunggu penjadwalan.
     * Ini adalah tahap pertama setelah mahasiswa mendaftar sempro.
     */
    public function getWaitingForSchedule()
    {
        // Query diubah untuk mencari Tugas Akhir dengan status 'draft'
        // DAN yang belum memiliki record sidang proposal.
        $query = Mahasiswa::whereHas('tugasAkhir', function ($q) {
            $q->where('status', 'draft')
                ->whereDoesntHave('sidang', function ($sidangQuery) {
                    $sidangQuery->where('jenis_sidang', 'proposal');
                });
        })->with(['user', 'tugasAkhir']);

        // Terapkan filter (pencarian & prodi) yang sudah ada
        $this->applyFilters($query);

        // Lakukan paginasi
        return $query->paginate($this->perPage, ['*'], 'menunggu_page')->appends($this->request->query());
    }

    /**
     * Mengambil jadwal sidang sempro yang sudah ditentukan.
     */
    public function getScheduled()
    {
        $query = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->whereHas('sidang', function ($q) {
            $q->where('jenis_sidang', 'proposal')
                ->where('status', 'dijadwalkan')
                ->where('is_active', true);
        });

        // Filter diterapkan pada relasi mahasiswa
        $this->applyFilters($query, 'sidang.tugasAkhir.mahasiswa');

        return $query->paginate($this->perPage, ['*'], 'jadwal_page')->appends($this->request->query());
    }

    /**
     * Mengambil mahasiswa yang tidak lulus sempro.
     */
    public function getFailed()
    {
        $query = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')
                ->where('status', 'tidak_lulus');
        })->with('user', 'tugasAkhir.sidangTerakhir');

        $this->applyFilters($query);

        return $query->paginate($this->perPage, ['*'], 'tidaklulus_page')->appends($this->request->query());
    }

    /**
     * Mengambil mahasiswa yang lulus sempro.
     */
    public function getPassed()
    {
        $query = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')
                ->whereIn('status', ['lulus', 'lulus_revisi']);
        })->with('user', 'tugasAkhir.sidangTerakhir.jadwalSidang');

        $this->applyFilters($query);

        return $query->paginate($this->perPage, ['*'], 'lulus_page')->appends($this->request->query());
    }

    /**
     * Menerapkan filter prodi dan search ke query.
     * Fungsi ini dibuat untuk prinsip DRY (Don't Repeat Yourself).
     */
    protected function applyFilters($query, $relationPath = null)
    {
        $prodi = $this->request->input('prodi');
        $search = $this->request->input('search');

        $filterLogic = function ($q) use ($prodi, $search) {
            if ($prodi) {
                $q->where('prodi', 'like', $prodi . '%');
            }
            if ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nim', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($q3) use ($search) {
                            $q3->where('name', 'like', '%' . $search . '%');
                        });
                });
            }
        };

        if ($relationPath) {
            $query->whereHas($relationPath, $filterLogic);
        } else {
            $query->where($filterLogic);
        }
    }
}
