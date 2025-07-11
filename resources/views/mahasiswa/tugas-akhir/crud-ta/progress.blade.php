@extends('layouts.template.main')
@section('title', 'Progress Tugas Akhir')

@push('styles')
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .progress-animated {
            background-size: 1rem 1rem;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
            animation: progress-bar-stripes 1s linear infinite;
        }

        .icon-box {
            width: 60px;
            height: 60px;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('mahasiswa.tugas-akhir.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active">Progress Tugas Akhir</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 fw-bold text-dark">Progress Tugas Akhir</h1>
            <p class="text-muted">Pantau perkembangan dan status tugas akhir Anda.</p>
        </div>
    </div>

    {{-- ====================================================================== --}}
    {{-- KONDISI 1: Mahasiswa BELUM memiliki data Tugas Akhir yang aktif --}}
    {{-- ====================================================================== --}}
    @if (!$tugasAkhir || $tugasAkhir->status === 'dibatalkan')
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg card-hover">
                    <div class="card-body text-center p-5">
                        <div
                            class="icon-box mx-auto mb-4 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt text-primary fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Belum Ada Data Tugas Akhir</h3>
                        <p class="text-muted mb-4">Anda belum memiliki data Tugas Akhir yang aktif. Mulai perjalanan
                            akademik Anda sekarang!</p>
                        <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                            <a href="{{ route('mahasiswa.tugas-akhir.ajukan') }}"
                                class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                                <i class="fas fa-file-upload me-2"></i> Ajukan Tugas Akhir
                            </a>
                            <a href="#"
                                class="btn btn-outline-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                                <i class="fas fa-lightbulb me-2"></i> Ambil Tawaran Topik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================== --}}
        {{-- KONDISI 2: Mahasiswa SUDAH memiliki data Tugas Akhir yang aktif --}}
        {{-- ====================================================================== --}}
    @else
        @php
            $ta = $tugasAkhir; // Alias untuk kemudahan
        @endphp

        @if ($ta->peranDosenTa->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 48px; height: 48px; background-color: rgba(13,110,253,0.1);">
                                <i class="bi bi-people-fill text-primary fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Dosen Pembimbing</h5>
                            <small class="text-muted">Tim pembimbing tugas akhir Anda</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach ($ta->peranDosenTa as $peranDosen)
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100 shadow-sm rounded-3">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div>
                                            <span
                                                class="badge bg-primary mb-1 text-capitalize">{{ str_replace('_', ' ', $peranDosen->peran) }}</span>
                                            <h6 class="mb-0 fw-semibold">{{ $peranDosen->dosen->user->name ?? '-' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning d-flex align-items-center gap-2 shadow-sm border-0 rounded-3">
                <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
                <div>
                    <strong>Belum Ada Pembimbing.</strong> Menunggu penunjukan oleh admin.
                </div>
            </div>
        @endif

        @include('mahasiswa.tugas-akhir.partials.progress-card', ['tugasAkhir' => $ta])

        @include('mahasiswa.tugas-akhir.partials.upload-modal', ['tugasAkhir' => $ta])

        @if ($ta->status === 'disetujui' || $ta->status === 'revisi')
            @include('mahasiswa.tugas-akhir.partials.cancel-form', ['tugasAkhir' => $ta])
        @endif

    @endif
@endsection
