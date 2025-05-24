@extends('layouts.template.mahasiswa')

@section('title', 'Dashboard Tugas Akhir')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="position-relative overflow-hidden rounded-3 mb-4 p-4"
            style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
            <div class="position-relative z-1">
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-graduation-cap me-2"></i> Dashboard Tugas Akhir
                </h4>
                <p class="text-muted mb-0">Kelola seluruh proses Tugas Akhir dari pengajuan hingga pemantauan progress.</p>
            </div>
            <i class="fas fa-book-open text-primary position-absolute opacity-10"
                style="font-size: 7rem; right: 1.5rem; bottom: -1rem;"></i>
        </div>

        <!-- Cards -->
        <div class="row g-4">
            <!-- Ajukan Topik Mandiri -->
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('ajukan-ta') }}" class="text-decoration-none">
                    <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">Ajukan Topik Mandiri</h6>
                            </div>
                            <p class="mb-0 text-muted">Ajukan topik tugas akhir mandiri ke dosen pembimbing.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ajukan Topik Dosen -->
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('list-topik') }}" class="text-decoration-none">
                    <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon icon-shape bg-info text-white rounded-circle me-3">
                                    <i class="fas fa-list-alt"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">Topik Dosen</h6>
                            </div>
                            <p class="mb-0 text-muted">Ajukan topik yang ditawarkan dosen pembimbing.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Progress TA -->
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('tugas-akhir.progress') }}" class="text-decoration-none">
                    <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">Progress TA</h6>
                            </div>
                            <p class="mb-0 text-muted">Pantau bimbingan dan revisi tugas akhir Anda.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- TA Dibatalkan -->
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('tugasAkhir.cancelled') }}" class="text-decoration-none">
                    <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon icon-shape bg-danger text-white rounded-circle me-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">TA Dibatalkan</h6>
                            </div>
                            <p class="mb-0 text-muted">Lihat riwayat tugas akhir yang dibatalkan atau ditolak.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .card-hover:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        .transition-scale {
            transition: transform 0.3s ease-in-out;
        }

        .transition-scale:hover {
            transform: scale(1.03);
        }

        .opacity-10 {
            opacity: 0.1;
        }

        .icon.icon-shape {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endsection