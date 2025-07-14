@extends('layouts.template.main')

@section('title', 'Dasbor Bimbingan')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        {{-- Judul dinamis berdasarkan mode --}}
                        @if (request('mode') == 'pantau_semua')
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-display-fill text-success me-2"></i>
                                Pantau Semua Bimbingan
                            </h2>
                            <p class="text-muted mb-0 small">
                                Daftar semua mahasiswa bimbingan yang aktif di semua program studi
                            </p>
                        @else
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                Mahasiswa Bimbingan Saya
                            </h2>
                            <p class="text-muted mb-0 small">
                                Daftar semua mahasiswa bimbingan Anda yang aktif
                            </p>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-calendar-event"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigasi Tab untuk Kaprodi/Kajur --}}
        @can('pantau-semua-bimbingan')
            {{-- Sesuaikan dengan permission Anda --}}
            <div class="row mb-4">
                <div class="col-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('mode') ? 'active' : '' }}"
                                href="{{ route('dosen.bimbingan.index') }}">
                                Bimbingan Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('mode') == 'pantau_semua' ? 'active' : '' }}"
                                href="{{ route('dosen.bimbingan.index', ['mode' => 'pantau_semua']) }}">
                                Pantau Semua
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endcan


        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <form method="GET"
                            class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center">

                            {{-- Hidden input untuk menjaga mode saat filter --}}
                            @if (request('mode'))
                                <input type="hidden" name="mode" value="{{ request('mode') }}">
                            @endif

                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 text-muted small fw-medium">Filter:</label>
                                <div class="btn-group" role="group" aria-label="Filter Prodi">
                                    <a href="{{ route('dosen.bimbingan.index', array_merge(request()->except('prodi'), ['prodi' => null])) }}"
                                        class="btn btn-sm {{ !request('prodi') ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Semua
                                    </a>
                                    <a href="{{ route('dosen.bimbingan.index', array_merge(request()->all(), ['prodi' => 'D4'])) }}"
                                        class="btn btn-sm {{ request('prodi') == 'D4' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        D4
                                    </a>
                                    <a href="{{ route('dosen.bimbingan.index', array_merge(request()->all(), ['prodi' => 'D3'])) }}"
                                        class="btn btn-sm {{ request('prodi') == 'D3' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        D3
                                    </a>
                                </div>
                            </div>

                            <div class="input-group flex-grow-1" style="max-width: 400px;">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Cari nama mahasiswa atau NIM..." value="{{ request('search') }}">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-list-ul text-primary me-2"></i>
                                Daftar Mahasiswa
                            </h5>
                            <span class="badge bg-light text-dark border">
                                Total: {{ $mahasiswaList->count() }} mahasiswa
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 border-0">
                                            <span class="fw-semibold">No</span>
                                        </th>
                                        <th scope="col" class="py-3 border-0">
                                            <span class="fw-semibold">Nama Mahasiswa</span>
                                        </th>
                                        <th scope="col" class="py-3 border-0">
                                            <span class="fw-semibold">Judul Tugas Akhir</span>
                                        </th>
                                        {{-- Kolom dinamis berdasarkan mode --}}
                                        @if (request('mode') == 'pantau_semua')
                                            <th scope="col" class="py-3 border-0">
                                                <span class="fw-semibold">Dosen Pembimbing</span>
                                            </th>
                                        @else
                                            <th scope="col" class="text-center py-3 border-0">
                                                <span class="fw-semibold">Peran Anda</span>
                                            </th>
                                        @endif
                                        <th scope="col" class="text-center py-3 border-0 pe-4">
                                            <span class="fw-semibold">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswaList as $tugasAkhir)
                                        <tr class="border-bottom">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="badge bg-light text-dark rounded-circle"
                                                        style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-initial bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person-fill"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $tugasAkhir->mahasiswa->user->name ?? 'Data tidak lengkap' }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $tugasAkhir->mahasiswa->nim ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    <p class="mb-0 lh-sm">{{ Str::limit($tugasAkhir->judul ?? '-', 60) }}
                                                    </p>
                                                </div>
                                            </td>

                                            {{-- Konten kolom dinamis --}}
                                            @if (request('mode') == 'pantau_semua')
                                                <td class="py-3">
                                                    <div>
                                                        <small><span class="fw-semibold">P1:</span>
                                                            {{ $tugasAkhir->pembimbingSatu->dosen->user->name ?? '-' }}</small>
                                                    </div>
                                                    <div>
                                                        <small><span class="fw-semibold">P2:</span>
                                                            {{ $tugasAkhir->pembimbingDua->dosen->user->name ?? '-' }}</small>
                                                    </div>
                                                </td>
                                            @else
                                                <td class="text-center py-3">
                                                    @php
                                                        // Ambil data dosen yang sedang login, jika ada.
                                                        $dosenAuth = auth()->user()->dosen;

                                                        // Inisialisasi peran sebagai null untuk keamanan.
                                                        $peranDosenIni = null;

                                                        // Hanya jalankan query jika user yang login adalah dosen.
                                                        if ($dosenAuth) {
                                                            // 1. Gunakan relasi yang benar: dosenPembimbing.
                                                            // 2. Filter koleksi untuk menemukan dosen yang sedang login.
                                                            $pembimbingIni = $tugasAkhir->dosenPembimbing->firstWhere(
                                                                'id',
                                                                $dosenAuth->id,
                                                            );

                                                            // 3. Jika ditemukan, ambil perannya dari data 'pivot'.
                                                            if ($pembimbingIni) {
                                                                // Variabel $peranDosenIni sekarang berisi STRING, contoh: "pembimbing1"
                                                                $peranDosenIni = $pembimbingIni->pivot->peran;
                                                            }
                                                        }
                                                    @endphp

                                                    {{-- ✅ PERBAIKAN: Gunakan variabel $peranDosenIni sebagai string biasa --}}
                                                    @if ($peranDosenIni)
                                                        <span
                                                            class="badge {{ $peranDosenIni === 'pembimbing1' ? 'bg-primary' : 'bg-info' }} rounded-pill px-3 py-2">
                                                            <i class="bi bi-person-badge me-1"></i>
                                                            {{ $peranDosenIni === 'pembimbing1' ? 'Pembimbing 1' : 'Pembimbing 2' }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="text-center py-3 pe-4">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('dosen.bimbingan.show', $tugasAkhir) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="bi bi-eye me-1"></i>
                                                        Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="mb-3">
                                                        <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                                                    </div>
                                                    <h6 class="text-muted mb-2">Tidak ada data mahasiswa</h6>
                                                    <p class="text-muted small mb-0">
                                                        Tidak ada mahasiswa bimbingan yang sesuai dengan filter Anda.
                                                    </p>
                                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
