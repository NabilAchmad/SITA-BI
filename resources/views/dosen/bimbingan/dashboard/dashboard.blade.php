@extends('layouts.template.main')

@section('title', 'Dashboard Bimbingan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Daftar Jadwal Bimbingan</h5>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah dijadwalkan bimbingan.</p>
            </div>
        </div>

        {{-- Tabs Program Studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}" href="?">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}" href="?prodi=D4">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}" href="?prodi=D3">D3</a>
            </li>
        </ul>

        {{-- Form Cari Nama Mahasiswa --}}
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Program Studi</th>
                        <th scope="col">Judul Tugas Akhir</th>
                        <th scope="col">Peran</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswaList as $item)
                        @php
                            $mhs = $item->tugasAkhir->mahasiswa ?? null;
                            $ta = $item->tugasAkhir ?? null;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mhs?->user?->name ?? '-' }}</td>
                            <td>{{ $mhs?->nim ?? '-' }}</td>
                            <td>
                                @if ($mhs->prodi === 'd4')
                                    D4 Bahasa Inggris
                                @elseif ($mhs->prodi === 'd3')
                                    D3 Bahasa Inggris
                                @else
                                    {{ $mhs->prodi }}
                                @endif
                            </td>
                            <td>{{ $ta?->judul ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    @if ($item->peran === 'pembimbing1')
                                        Pembimbing 1
                                    @elseif($item->peran === 'pembimbing2')
                                        Pembimbing 2
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="{{ route('bimbingan.detail', $mhs->id) }}">
                                    <i class="bi bi-info-circle"></i>Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada mahasiswa yang Anda bimbing.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
@endsection
