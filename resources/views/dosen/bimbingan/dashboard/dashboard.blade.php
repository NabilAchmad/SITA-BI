@extends('layouts.template.main')

@section('title', 'Dasbor Bimbingan')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h2 class="fw-bold mb-1">
                            <i class="bi bi-people-fill text-primary me-2"></i>
                            Mahasiswa Bimbingan
                        </h2>
                        <p class="text-muted mb-0 small">
                            Daftar semua mahasiswa bimbingan Anda yang aktif
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-calendar-event"></i>
                        <span>{{ date('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <form method="GET"
                            class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center">
                            <!-- Filter Prodi -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 text-muted small fw-medium">Filter:</label>
                                <div class="btn-group" role="group" aria-label="Filter Prodi">
                                    <a href="{{ route('dosen.bimbingan.index', ['search' => request('search')]) }}"
                                        class="btn btn-sm {{ !request('prodi') ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Semua
                                    </a>
                                    <a href="{{ route('dosen.bimbingan.index', ['prodi' => 'D4', 'search' => request('search')]) }}"
                                        class="btn btn-sm {{ request('prodi') == 'D4' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        D4
                                    </a>
                                    <a href="{{ route('dosen.bimbingan.index', ['prodi' => 'D3', 'search' => request('search')]) }}"
                                        class="btn btn-sm {{ request('prodi') == 'D3' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        D3
                                    </a>
                                </div>
                            </div>

                            <!-- Search Box -->
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

        <!-- Main Content -->
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
                                            <span class="fw-semibold">NIM</span>
                                        </th>
                                        <th scope="col" class="py-3 border-0">
                                            <span class="fw-semibold">Judul Tugas Akhir</span>
                                        </th>
                                        <th scope="col" class="text-center py-3 border-0">
                                            <span class="fw-semibold">Peran Anda</span>
                                        </th>
                                        <th scope="col" class="text-center py-3 border-0 pe-4">
                                            <span class="fw-semibold">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ✅ PERBAIKAN: Loop melalui $mahasiswaList, di mana setiap item adalah objek $tugasAkhir --}}
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
                                                            {{-- ✅ PERBAIKAN: Mengakses data langsung dari $tugasAkhir --}}
                                                            {{ $tugasAkhir->mahasiswa->user->name ?? 'Data tidak lengkap' }}
                                                        </div>
                                                        <small class="text-muted">Mahasiswa Aktif</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-white border border-secondary border-opacity-25 px-3 py-2">
                                                    {{-- ✅ PERBAIKAN: Mengakses data langsung dari $tugasAkhir --}}
                                                    {{ $tugasAkhir->mahasiswa->nim ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    <p class="mb-0 lh-sm">
                                                        {{-- ✅ PERBAIKAN: Mengakses data langsung dari $tugasAkhir --}}
                                                        {{ Str::limit($tugasAkhir->judul ?? '-', 60) }}
                                                    </p>
                                                    @if (strlen($tugasAkhir->judul ?? '') > 60)
                                                        <small class="text-muted">...</small>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Konten kolom dinamis --}}
                                            @if (request('mode') == 'pantau_semua')
                                                <td class="py-3">
                                                    <div>
                                                        <small><span class="fw-semibold">P1:</span>
                                                            {{ $tugasAkhir->pembimbingSatu->user->name ?? '-' }}</small>
                                                    </div>
                                                    <div>
                                                        <small><span class="fw-semibold">P2:</span>
                                                            {{ $tugasAkhir->pembimbingDua->user->name ?? '-' }}</small>
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
                                                    {{-- ✅ PERBAIKAN: Meneruskan objek $tugasAkhir ke route --}}
                                                    <a href="{{ route('dosen.bimbingan.show', $tugasAkhir) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Lihat detail bimbingan">
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

    <!-- Tooltip initialization script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
