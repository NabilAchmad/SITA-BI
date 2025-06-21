@extends('layouts.template.main')

@section('title', 'Tugas Akhir Dibatalkan')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-danger mb-1">
                    <i class="bi bi-x-circle-fill me-2"></i> Tugas Akhir Dibatalkan
                </h2>
                <p class="text-muted mb-0">Riwayat pengajuan Tugas Akhir yang dibatalkan oleh Anda.</p>
            </div>
            <a href="{{ route('tugas-akhir.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard TA
            </a>
        </div>

        @if ($tugasAkhirDibatalkan->isEmpty())
            <div class="alert alert-info text-center rounded-3 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Tidak ada riwayat pembatalan Tugas Akhir.
            </div>
        @endif

        <div class="row justify-content-center">
            @foreach ($tugasAkhirDibatalkan as $ta)
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-danger bg-opacity-75 text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-journal-x me-2"></i> {{ $ta->judul }}
                                </h5>
                                <small class="text-white-50">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </small>
                            </div>
                        </div>

                        <div class="card-body bg-light p-4">
                            <div class="mb-3">
                                <h6 class="fw-bold text-secondary mb-1">Abstrak</h6>
                                <p class="text-muted mb-0">{{ $ta->abstrak }}</p>
                            </div>
                            <hr>
                            <div>
                                <h6 class="fw-bold text-secondary mb-1">Alasan Pembatalan</h6>
                                <p class="text-danger fst-italic mb-0">
                                    {{ $ta->alasan_pembatalan ?? 'Tidak ada alasan yang diberikan.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
