@extends('layouts.template.main')
@section('title', 'Jadwal Sidang Sempro')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-x me-2"></i> Belum Punya Jadwal Seminar Proposal
                </h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah terdaftar seminar proposal, namun belum memiliki
                    jadwal.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Belum Dijadwalkan Seminar Proposal</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Judul TA</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswa as $index => $mhs)
                                @php
                                    // Ganti cek sidang untuk jenis proposal dan status menunggu
                                    $sidang = $mhs->tugasAkhir->sidang->firstWhere(function ($s) {
                                        return $s->jenis_sidang === 'proposal' && $s->status === 'menunggu';
                                    });
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $mhs->user->name }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($sidang)
                                            <button type="button" class="btn btn-sm btn-primary btn-jadwalkan"
                                                data-sidang-id="{{ $sidang->id }}" data-nama="{{ $mhs->user->name }}"
                                                data-nim="{{ $mhs->nim }}" data-judul="{{ $mhs->tugasAkhir->judul }}"
                                                data-url="{{ route('jadwal-seminar.simpanPenguji', ['sidang_id' => $sidang->id]) }}">
                                                <i class="bi bi-calendar-plus me-1"></i> Jadwalkan
                                            </button>
                                        @else
                                            <span class="text-muted fst-italic">Tidak ada seminar proposal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i> Tidak ada mahasiswa yang menunggu
                                        penjadwalan seminar proposal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.sidang.sempro.modal.pilih-penguji')
    @include('admin.sidang.sempro.modal.form-jadwal')

@endsection
