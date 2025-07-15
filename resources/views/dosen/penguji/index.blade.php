@extends('layouts.template.main')

{{-- Bagian untuk judul halaman --}}
@section('title', 'Daftar Sidang Ujian')

{{-- Bagian untuk konten utama halaman --}}
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Daftar Sidang Ujian</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('dosen/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sidang Ujian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h5 class="card-title mb-0">Mahasiswa yang Akan Diuji</h5>
                    </div>
                    <div class="card-body px-0 py-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 5%;">
                                            <span class="fw-semibold">No</span>
                                        </th>
                                        <th scope="col" style="width: 30%;">
                                            <span class="fw-semibold">Nama Mahasiswa</span>
                                        </th>
                                        <th scope="col" style="width: 40%;">
                                            <span class="fw-semibold">Judul Tugas Akhir</span>
                                        </th>
                                        <th scope="col" class="text-center" style="width: 15%;">
                                            <span class="fw-semibold">Peran Anda</span>
                                        </th>
                                        <th scope="col" class="text-center" style="width: 10%;">
                                            <span class="fw-semibold">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Loop data sidang dari controller --}}
                                    @forelse ($daftarSidang as $sidang)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-initial bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person-fill fs-5"></i>
                                                    </div>
                                                    <div>
                                                        {{-- Gunakan optional helper (?->) untuk keamanan jika ada data relasi yang kosong --}}
                                                        <div class="fw-semibold">
                                                            {{ $sidang->tugasAkhir?->mahasiswa?->user?->name ?? 'Data Mahasiswa Tidak Ditemukan' }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $sidang->tugasAkhir?->mahasiswa?->nim ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-wrap">
                                                    <p class="mb-0 lh-sm">
                                                        {{ $sidang->tugasAkhir?->judul ?? 'Judul tidak tersedia' }}</p>
                                                </div>
                                            </td>
                                            <td class="text-center py-3">
                                                @php
                                                    // 1. Inisialisasi variabel peran.
                                                    $peranDosenIni = null;

                                                    // 2. Ambil ID dosen yang sedang login (gunakan optional() untuk keamanan).
                                                    $dosenAuthId = optional(auth()->user()->dosen)->id;

                                                    // 3. Ambil koleksi dosen dari relasi 'dosenPembimbing'.
                                                    $semuaDosen = $sidang->tugasAkhir->dosenPembimbing;

                                                    // 4. Lakukan pencarian HANYA jika ID & koleksi dosennya ada.
                                                    if ($dosenAuthId && $semuaDosen) {
                                                        // Cari dosen yang cocok di dalam koleksi.
                                                        $dosenIni = $semuaDosen->firstWhere('id', $dosenAuthId);

                                                        // Jika dosen ditemukan, ambil perannya dari data 'pivot'.
                                                        if ($dosenIni) {
                                                            $peranDosenIni = $dosenIni->pivot->peran;
                                                        }
                                                    }
                                                @endphp

                                                {{-- Sekarang tampilkan hasilnya menggunakan variabel $peranDosenIni --}}
                                                @if ($peranDosenIni)
                                                    @php
                                                        // Logika untuk mengubah string peran menjadi teks yang lebih ramah
                                                        $badgeText = Str::of($peranDosenIni)
                                                            ->replaceMatches('/[0-9]+/', ' $0') // 'penguji1' -> 'penguji 1'
                                                            ->title(); // 'penguji 1' -> 'Penguji 1'

                                                        $badgeClass = Str::startsWith($peranDosenIni, 'pembimbing')
                                                            ? 'bg-primary'
                                                            : 'bg-success';
                                                    @endphp

                                                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                                        <i class="bi bi-person-badge me-1"></i>
                                                        {{ $badgeText }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic small">- Anda tidak memiliki peran di
                                                        sini -</span>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                {{-- Tombol ini akan mengarah ke halaman detail dan penilaian --}}
                                                <a href="{{ route('dosen.penguji.show', $sidang->id) }}"
                                                    class="btn btn-sm btn-primary rounded-pill px-3">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    [Image of an empty inbox icon]
                                                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                                                    <h6 class="text-muted mb-1 mt-3">Tidak Ada Jadwal Sidang</h6>
                                                    <p class="text-muted small">Saat ini tidak ada jadwal sidang yang
                                                        menugaskan Anda sebagai penguji.</p>
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
@endsection
