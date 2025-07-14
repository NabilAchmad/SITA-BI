@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="position-relative overflow-hidden rounded-3 mb-4 p-4" style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
        <div class="position-relative z-1">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Sidang
            </h4>
            <p class="text-muted mb-0">Kelola seluruh proses sidang dari pendaftaran hingga pemantauan nilai dan jadwal.</p>
        </div>
        <i class="fas fa-clipboard-check text-primary position-absolute opacity-10" style="font-size: 7rem; right: 1.5rem; bottom: -1rem;"></i>
    </div>

    <!-- Sidang Cards -->
   <div class="row g-4">

    <!-- Daftar Sidang -->
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('mahasiswa.sidang.daftar-akhir') }}" class="text-decoration-none">
            <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-dark">Daftar Sidang Akhir</h6>
                    </div>
                    <p class="mb-0 text-muted">Ajukan permohonan sidang akhir sesuai persyaratan.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Nilai Sidang -->
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('mahasiswa.sidang.nilai') }}" class="text-decoration-none">
            <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-dark">Nilai Sidang</h6>
                    </div>
                    <p class="mb-0 text-muted">Lihat hasil penilaian sidang Anda dari dosen penguji.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Jadwal Sidang -->
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('mahasiswa.sidang.jadwal') }}" class="text-decoration-none">
            <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-dark">Jadwal Sidang</h6>
                    </div>
                    <p class="mb-0 text-muted">Pantau waktu dan lokasi pelaksanaan sidang Anda.</p>
                </div>
            </div>
        </a>
        
    </div>
    

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
</style>
@endsection
