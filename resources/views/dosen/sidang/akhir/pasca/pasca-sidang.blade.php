@extends('layouts.template.main')
@section('title', 'Data Pasca Sidang')
@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Pasca Sidang Akhir</h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah menyelesaikan sidang akhir.</p>
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
                    href="{{ route('pasca.sidang.akhir') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('pasca.sidang.akhir', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('pasca.sidang.akhir', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        <form action="{{ route('pasca.sidang.akhir') }}" method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM..."
                    value="{{ request('search') }}">
                @if (request('prodi'))
                    <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                @endif
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="tab-content" id="jadwalTabContent">
                    <div class="tab-pane fade show active" id="dijadwalkan" role="tabpanel"
                        aria-labelledby="dijadwalkan-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Tanggal Sidang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="pascaSidang">
                                    @forelse ($sidangSelesai as $index => $item)
                                        @php
                                            $sidang = $item->sidang;
                                            $ta = $sidang->tugasAkhir ?? null;
                                            $mhs = $ta?->mahasiswa;
                                        @endphp

                                        <tr>
                                            <td class="text-center">
                                                {{ ($sidangSelesai->currentPage() - 1) * $sidangSelesai->perPage() + $index + 1 }}
                                            </td>
                                            <td>{{ $mhs?->user?->name ?? '-' }}</td>
                                            <td>{{ $mhs->nim ?? '-' }}</td>
                                            <td>{{ $ta->judul ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-primary btn-sm">
                                                    Cetak
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada sidang yang selesai.
                                            </td>
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
        {{ $sidangSelesai->links() }}
    </div>

@endsection
