@extends('layouts.template.main')
@section('title', 'Mahasiswa Lulus Sempro')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-success"><i class="bi bi-mortarboard me-2"></i> Mahasiswa Lulus Sempro</h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah dinyatakan lulus Sidang Proposal.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('arsip-ta.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lulus Sempro</li>
                </ol>
            </nav>
        </div>

        {{-- Filter Prodi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('arsip.lulus.sempro') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('arsip.lulus.sempro', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('arsip.lulus.sempro', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        {{-- Search --}}
        <form action="{{ route('arsip.lulus.sempro') }}" method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-success">Cari</button>
            </div>
        </form>

        {{-- Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Program Studi</th>
                                <th>Judul Proposal</th>
                                <th>Tanggal Sidang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswaLulus as $mahasiswa)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($mahasiswaLulus->firstItem() - 1) }}</td>
                                    <td>{{ $mahasiswa->user->name }}</td>
                                    <td>{{ $mahasiswa->nim }}</td>
                                    <td>{{ $mahasiswa->program_studi }}</td>
                                    <td>{{ $mahasiswa->tugasAkhir->judul ?? '-' }}</td>
                                    <td class="text-center">
                                        {{ $mahasiswa->sidangSempro->tanggal ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Lulus</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada mahasiswa yang lulus sempro.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $mahasiswaLulus->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
