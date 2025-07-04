@extends('layouts.template.main')

@section('title', 'Daftar Mahasiswa Sudah Memiliki Pembimbing')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            @include('admin.mahasiswa.breadcrumbs.navlink')
            <div class="text-center mt-5">
                <h4 class="card-title text-primary mb-0">Daftar Mahasiswa Sudah Memiliki Pembimbing</h4>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Prodi -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    {{-- Asumsi route name adalah 'penugasan-pembimbing.list' --}}
                    <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                        href="{{ route('list-mahasiswa') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                        href="{{ route('list-mahasiswa', ['prodi' => 'D4']) }}">D4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                        href="{{ route('list-mahasiswa', ['prodi' => 'D3']) }}">D3</a>
                </li>
            </ul>

            <!-- Search -->
            <form method="GET" action="{{ route('list-mahasiswa') }}" class="row g-2 mb-3 justify-content-end">
                <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama atau NIM..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>

            <!-- Tabel Mahasiswa -->
            <div class="table-responsive">
                <table class="table table-bordered shadow-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Judul Tugas Akhir</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ========================================================================= --}}
                        {{-- PERBAIKAN: Iterasi menggunakan $tugasAkhirList sesuai data dari controller --}}
                        {{-- ========================================================================= --}}
                        @forelse ($tugasAkhirList as $index => $ta)
                            @php
                                // Helper untuk membuat kode di dalam <td> lebih bersih
                                $mahasiswa = $ta->mahasiswa;
                                $pembimbing1 = $ta->peranDosenTA->where('peran', 'pembimbing1')->first();
                                $pembimbing2 = $ta->peranDosenTA->where('peran', 'pembimbing2')->first();
                            @endphp
                            <tr>
                                {{-- PERBAIKAN: Menggunakan $tugasAkhirList untuk paginasi --}}
                                <td>{{ ($tugasAkhirList->firstItem() ?? 0) + $index }}</td>
                                <td>{{ $mahasiswa->user->name }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ strtoupper($mahasiswa->prodi) }} Bahasa Inggris</td>
                                <td>{{ $ta->judul ?? '-' }}</td>
                                {{-- PERBAIKAN: Menggunakan helper untuk menampilkan nama pembimbing --}}
                                <td>{{ $pembimbing1->dosen->user->name ?? '-' }}</td>
                                <td>{{ $pembimbing2->dosen->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditPembimbing-{{ $ta->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada mahasiswa dengan pembimbing.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                {{ $tugasAkhirList->links() }}
            </div>
        </div>
    </div>

    <!-- Semua Modal Ditempatkan di Luar Tabel -->
    {{-- PERBAIKAN: Iterasi menggunakan $tugasAkhirList untuk menyertakan modal --}}
    @foreach ($tugasAkhirList as $ta)
        @include('admin.mahasiswa.partials.modal-edit-pembimbing', [
            'tugasAkhir' => $ta,
            'dosenList' => $dosenList, // Pastikan nama variabel 'dosen' menjadi 'dosenList'
        ])
    @endforeach
@endsection
