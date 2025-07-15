@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@section('content')
    <div class="container-fluid py-4">

        <div class="position-relative overflow-hidden rounded-3 mb-4 p-4"
            style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
            <div class="position-relative z-1">
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Sidang
                </h4>
                <p class="text-muted mb-0">Kelola seluruh proses sidang dari pendaftaran hingga pemantauan nilai dan jadwal.
                </p>
            </div>
            <i class="fas fa-clipboard-check text-primary position-absolute opacity-10"
                style="font-size: 5rem; right: 1.5rem; bottom: 1rem;"></i>
        </div>

        <ul class="nav nav-tabs nav-fill mb-4" id="sidangTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="jadwal-sidang-tab" data-bs-toggle="tab" data-bs-target="#jadwal-sidang"
                    type="button" role="tab" aria-controls="jadwal-sidang" aria-selected="true">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Sidang
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nilai-sidang-tab" data-bs-toggle="tab" data-bs-target="#nilai-sidang"
                    type="button" role="tab" aria-controls="nilai-sidang" aria-selected="false">
                    <i class="fas fa-star-half-alt me-2"></i>Nilai Sidang
                </button>
            </li>
        </ul>

        <div class="tab-content" id="sidangTabContent">

            {{-- Ganti bagian ini di dalam view Anda --}}
            <div class="tab-pane fade show active" id="jadwal-sidang" role="tabpanel" aria-labelledby="jadwal-sidang-tab">

                {{-- Cek apakah variabel $jadwal ada dan tidak null --}}
                @if ($jadwal)

                    {{-- Jika JADWAL DITEMUKAN, tampilkan detailnya --}}
                    <div class="card border-light-subtle shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2"></i>Jadwal Sidang Anda</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                {{-- Detail Waktu dan Tanggal --}}
                                <div class="col-md-6">
                                    <h6 class="text-muted fw-bold"><i class="fas fa-clock me-2"></i>Waktu Pelaksanaan</h6>
                                    <p class="fs-5 fw-bold text-dark mb-1">
                                        {{-- Format tanggal menjadi lebih mudah dibaca (misal: Senin, 15 Juli 2025) --}}
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="fs-5 text-primary">
                                        Pukul {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB
                                    </p>
                                </div>

                                {{-- Detail Lokasi --}}
                                <div class="col-md-6">
                                    <h6 class="text-muted fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Sidang
                                    </h6>
                                    <p class="fs-5 fw-bold text-dark mb-0">
                                        {{-- Gunakan optional() untuk keamanan jika relasi ruangan null --}}
                                        {{ optional($jadwal->ruangan)->nama_ruangan ?? 'Ruangan belum ditentukan' }}
                                    </p>
                                    <p class="text-muted">
                                        {{ optional($jadwal->ruangan)->lokasi ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Detail Tim Penguji --}}
                            <div>
                                <h6 class="text-muted fw-bold mb-3"><i class="fas fa-users me-2"></i>Tim Dosen Penguji</h6>
                                <ul class="list-group list-group-flush">
                                    @php
                                        // Ambil data penguji dari relasi yang sudah di-load
                                        $pengujiList = $jadwal->sidang->tugasAkhir->peranDosenTa;
                                    @endphp

                                    @forelse ($pengujiList as $peran)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span
                                                class="fw-semibold">{{ optional($peran->dosen->user)->name ?? 'Nama Dosen Tidak Tersedia' }}</span>
                                            <span
                                                class="badge bg-primary rounded-pill">{{ Str::title(str_replace('_', ' ', $peran->peran)) }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item px-0 text-muted">Tim penguji belum ditetapkan.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Jika JADWAL TIDAK DITEMUKAN, tampilkan pesan ini --}}
                    <div class="card border-light-subtle shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold text-dark mb-3"><i
                                    class="fas fa-calendar-check me-2 text-warning"></i>Informasi Jadwal Sidang</h5>
                            <p class="card-text text-muted">
                                Di sini Anda dapat memantau waktu dan lokasi pelaksanaan sidang Anda. Informasi akan
                                diperbarui oleh administrator setelah pendaftaran Anda diverifikasi dan dijadwalkan.
                            </p>
                            <div class="alert alert-warning mt-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i> Saat ini belum ada jadwal sidang yang tersedia untuk
                                Anda.
                            </div>
                        </div>
                    </div>
                @endif
            </div>


            <div class="tab-pane fade" id="nilai-sidang" role="tabpanel" aria-labelledby="nilai-sidang-tab">
                <div class="card border-light-subtle shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold text-dark mb-3">
                            <i class="bi bi-award me-2 text-success"></i>Hasil Penilaian Sidang
                        </h5>
                        <p class="card-text text-muted">
                            Lihat hasil penilaian sidang Anda dari para dosen penguji. Nilai akan tersedia setelah sidang
                            selesai dilaksanakan dan dinilai oleh seluruh penguji.
                        </p>

                        {{-- Tampilkan tabel nilai HANYA jika data sidang dan nilainya ada --}}
                        @if ($sidang && $sidang->nilaiSidang->isNotEmpty())
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 5%;">No</th>
                                            <th scope="col">Nama Penguji</th>
                                            <th scope="col" class="text-center">Nilai (100)</th>
                                            <th scope="col">Komentar / Revisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sidang->nilaiSidang as $nilai)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $nilai->dosen?->user?->name ?? 'Dosen tidak ditemukan' }}</td>
                                                <td class="text-center fw-bold">{{ $nilai->skor }}</td>
                                                <td>{{ $nilai->komentar ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Bagian Total Nilai Akhir --}}
                            <div class="d-flex justify-content-end mt-4">
                                <div class="text-end" style="min-width: 250px;">
                                    <h6 class="text-muted mb-2">Total Nilai Akhir Sidang:</h6>
                                    <div class="card bg-success-subtle border-0">
                                        <div class="card-body text-center p-2">
                                            <h2 class="display-5 fw-bold text-success mb-0">
                                                {{ number_format($nilaiAkhir, 2) }}</h2>
                                        </div>
                                    </div>
                                    @if ($nilaiAkhir >= 60 && $nilaiAkhir <= 100)
                                        <small class="text-muted fst-italic">Selamat Anda Lulus Sidang.</small>
                                    @elseif($nilaiAkhir < 60)
                                        <small class="text-muted fst-italic">Harap Mengulang Sidang.</small>
                                    @endif
                                    <br>
                                    <small class="text-muted fst-italic">Nilai akhir adalah akumulasi dari bobot nilai
                                        setiap penguji.</small>
                                </div>
                            </div>
                        @else
                            {{-- Tampilkan ini jika belum ada nilai yang masuk --}}
                            <div class="alert alert-info mt-4" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i> Belum ada data nilai yang dipublikasikan.
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 600;
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-link:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background-color: #f8f9fa;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush
