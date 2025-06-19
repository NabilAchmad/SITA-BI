@extends('layouts.template.main')
@section('title', 'Jadwal Sidang Akhir')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Jadwal Sidang Sempro</h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah dijadwalkan sidang sempro.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal</li>
                </ol>
            </nav>
        </div>

        {{-- Tabs Program Studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        <form action="{{ route('jadwal.sidang.sempro') }}" method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="tab-content" id="jadwalTabContent">
                    {{-- Tab dijadwalkan --}}
                    <div class="tab-pane fade show active" id="dijadwalkan" role="tabpanel"
                        aria-labelledby="dijadwalkan-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Ruangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="dijadwalkan">
                                    @forelse ($jadwalList as $index => $jadwal)
                                        @php
                                            $ta = $jadwal->sidang->tugasAkhir;
                                            $mahasiswa = $ta?->mahasiswa;
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration + ($jadwalList->firstItem() - 1) }}
                                            </td>
                                            </td>
                                            <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
                                            <td>{{ $ta?->judul ?? '-' }}</td>
                                            <td class="text-center">{{ $jadwal->tanggal }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                            </td>
                                            <td>{{ $jadwal->ruangan?->lokasi ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('jadwal-sempro.show', ['sidang_id' => $jadwal->sidang_id]) }}">Detail</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Belum ada jadwal sidang yang
                                                tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $jadwalList->links() }}
    </div>

@endsection
