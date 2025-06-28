@extends('layouts.template.main')
@section('title', 'List Topik Tugas Akhir')

@section('content')
    <div class="container-fluid">
        <!-- Header dan Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary">
                    <i class="bi bi-lightbulb me-2"></i> List Topik Tugas Akhir
                </h1>
                <p class="text-muted mb-0">Pilih topik tugas akhir yang sesuai dengan minat dan bidang keahlian Anda.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tugas-akhir.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Topik TA</li>
                </ol>
            </nav>
        </div>

        <!-- Search Box -->
        <form method="GET" action="{{ route('mahasiswa.topik.index') }}">
            <div class="input-group mb-4">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari topik atau deskripsi..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <!-- Tabel Topik -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Dosen</th>
                                <th>Topik</th>
                                <th>Deskripsi</th>
                                <th>Kuota</th>
                                @if (!$mahasiswaSudahPunyaTA)
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topikList as $topik)
                                <tr>
                                    <td class="text-center">
                                        {{ ($topikList->currentPage() - 1) * $topikList->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center">{{ $topik->user->name ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $topik->judul_topik }}</td>
                                    <td>{{ Str::limit($topik->deskripsi, 120) }}</td>
                                    <td class="text-center">{{ $topik->kuota }}</td>
                                    @if (!$mahasiswaSudahPunyaTA)
                                        <td class="text-center">
                                            <form action="{{ route('mahasiswa.topik.ambil', $topik->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success px-4">
                                                    <i class="bi bi-check-circle me-1"></i> Ambil Topik
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada topik yang tersedia saat
                                        ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-end">
                    {{ $topikList->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
