@extends('layouts.template.main')

@section('title', 'Progress Tugas Akhir')

@push('styles')
    {{-- Menambahkan style khusus untuk halaman ini --}}
    <style>
        .text-gradient {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .log-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-gradient">
                    <i class="bi bi-bar-chart-line-fill me-2"></i>Pusat Progress Tugas Akhir
                </h2>
                <p class="text-muted mb-0">Semua progres, jadwal, dan catatan bimbingan Anda ada di sini.</p>
            </div>

            <div>
                <a href="{{ route('mahasiswa.tugas-akhir.dashboard') }}"
                    class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard TA
                </a>
            </div>
        </div>

        {{-- KONDISI JIKA MAHASISWA BELUM PUNYA TA AKTIF --}}
        @if (!$tugasAkhir)
            @include('mahasiswa.tugas-akhir.partials._progress_empty')
        @else
            {{-- KONTEN UTAMA JIKA TA AKTIF --}}
            <div class="row g-4">
                {{-- Kolom Kiri: Log Bimbingan & Aksi Upload --}}
                <div class="col-lg-8">
                    <!-- Tombol Aksi Utama Mahasiswa -->
                    @if ($tugasAkhir->status === 'diajukan')
                        <div class="card shadow-sm border-0 rounded-4 mb-4 card-hover">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold">Menunggu Persetujuan Kaprodi</h5>
                                <p class="text-muted"><i class="bi bi-clock-fill me-2"></i>Judul tugas akhir anda sedang
                                    diajukan.</p>
                            </div>
                        </div>
                    @elseif($tugasAkhir->status === 'disetujui')
                        <div class="card shadow-sm border-0 rounded-4 mb-4 card-hover">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold">Langkah Anda Selanjutnya</h5>

                                @if ($jadwalAktif->isNotEmpty())
                                    <p class="text-muted"><i class="bi bi-info-circle me-2"></i>Anda sedang dalam sesi
                                        bimbingan. Silakan tunggu hingga sesi selesai sebelum mengunggah versi baru.</p>
                                    <button class="btn btn-secondary btn-lg rounded-pill shadow-sm" disabled>
                                        <i class="bi bi-cloud-slash me-2"></i> Upload Dinonaktifkan
                                    </button>
                                @else
                                    <p class="text-muted">Unggah file untuk memulai bimbingan dengan dosen anda.</p>
                                    <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#uploadFileModal{{ $tugasAkhir->id }}">
                                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Ganti File / Upload Versi Baru
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                    <!-- Log Bimbingan Terpusat -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi bi-chat-left-dots-fill me-2 text-primary"></i>Log Bimbingan
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Memuat partial untuk log bimbingan --}}
                            @include('mahasiswa.tugas-akhir.partials._log_bimbingan_mahasiswa', [
                                'catatanList' => $catatanList,
                                'tugasAkhir' => $tugasAkhir,
                            ])
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Panel Status & Jadwal --}}
                <div class="col-lg-4">
                    {{-- Memuat partial untuk panel status --}}
                    @include('mahasiswa.tugas-akhir.partials._panel_status_mahasiswa', [
                        'tugasAkhir' => $tugasAkhir,
                        'bimbinganCountP1' => $bimbinganCountP1,
                        'bimbinganCountP2' => $bimbinganCountP2,
                        'pembimbing1' => $pembimbing1,
                        'pembimbing2' => $pembimbing2,
                    ])
                </div>
            </div>
        @endif
    </div>

    {{-- Modal-modal yang sudah ada tetap dipanggil di sini --}}
    @if ($tugasAkhir)
        @include('mahasiswa.tugas-akhir.partials.upload-modal', ['tugasAkhir' => $tugasAkhir])
        @if ($tugasAkhir->status === 'disetujui' || $tugasAkhir->status === 'revisi')
            @include('mahasiswa.tugas-akhir.partials.cancel-form', ['tugasAkhir' => $tugasAkhir])
        @endif
        {{-- Modal untuk pengajuan perubahan jadwal --}}
        @include('mahasiswa.tugas-akhir.partials._modal_perubahan_jadwal', ['tugasAkhir' => $tugasAkhir])
    @endif

@endsection
