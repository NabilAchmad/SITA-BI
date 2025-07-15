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
                    <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                        href="{{ route('jurusan.penugasan-pembimbing.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                        href="{{ route('jurusan.penugasan-pembimbing.index', ['prodi' => 'D4']) }}">D4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                        href="{{ route('jurusan.penugasan-pembimbing.index', ['prodi' => 'D3']) }}">D3</a>
                </li>
            </ul>

            <!-- Search -->
            <form method="GET" action="{{ route('jurusan.penugasan-pembimbing.index') }}" class="row g-2 mb-3 justify-content-end">
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
                        @forelse ($tugasAkhirList as $index => $ta)
                            @php
                                // Helper untuk membuat kode di dalam <td> lebih bersih
                                $mahasiswa = $ta->mahasiswa;
                                // Menggunakan accessor yang sudah kita definisikan di model TugasAkhir
                                $pembimbing1 = $ta->pembimbing_satu;
                                $pembimbing2 = $ta->pembimbing_dua;
                            @endphp
                            <tr>
                                <td>{{ $tugasAkhirList->firstItem() + $index }}</td>
                                <td>{{ $mahasiswa->user->name }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ strtoupper($mahasiswa->prodi) }} Bahasa Inggris</td>
                                <td>{{ $ta->judul ?? '-' }}</td>
                                {{-- ✅ PERBAIKAN: Memanggil ->user langsung dari objek $pembimbing1 (Dosen) --}}
                                <td>{{ $pembimbing1?->dosen->user?->name ?? '-' }}</td>
                                {{-- ✅ PERBAIKAN: Memanggil ->user langsung dari objek $pembimbing2 (Dosen) --}}
                                <td>{{ $pembimbing2?->dosen->user?->name ?? '-' }}</td>
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
    @foreach ($tugasAkhirList as $ta)
        @include('admin.mahasiswa.partials.modal-edit-pembimbing', [
            'tugasAkhir' => $ta,
            'dosenList' => $dosenList,
        ])
    @endforeach
@endsection
