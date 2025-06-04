@extends('layouts.template.mahasiswa')

@section('title', 'Dashboard Bimbingan')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="position-relative overflow-hidden rounded-3 mb-4 p-4" style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
        <div class="position-relative z-1">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-users-cog me-2"></i> Dashboard Bimbingan
            </h4>
            <p class="text-muted mb-0">Kelola seluruh kegiatan bimbingan Tugas Akhir Anda dengan dosen pembimbing.</p>
        </div>
        <i class="fas fa-user-graduate text-primary position-absolute opacity-10" style="font-size: 7rem; right: 1.5rem; bottom: -1rem;"></i>
    </div>

    <!-- Bimbingan Cards -->
    <div class="row g-4">

        <!-- Ajukan Jadwal -->
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('bimbingan.ajukanJadwal') }}" class="text-decoration-none">
                <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h6 class="mb-0 fw-semibold text-dark">Ajukan Jadwal Bimbingan</h6>
                        </div>
                        <p class="mb-0 text-muted">Ajukan jadwal bimbingan baru dengan dosen pembimbing Anda.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Lihat Jadwal -->
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('jadwal.bimbingan') }}" class="text-decoration-none">
                <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon icon-shape bg-info text-white rounded-circle me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h6 class="mb-0 fw-semibold text-dark">Lihat Jadwal Bimbingan</h6>
                        </div>
                        <p class="mb-0 text-muted">Lihat semua jadwal bimbingan yang telah disetujui.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Ajukan Perubahan -->
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('ubah.jadwal') }}" class="text-decoration-none">
                <div class="card card-hover border border-dark-subtle shadow-sm h-100 transition-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h6 class="mb-0 fw-semibold text-dark">Ajukan Perubahan Jadwal</h6>
                        </div>
                        <p class="mb-0 text-muted">Ajukan perubahan jadwal jika tidak bisa mengikuti jadwal sebelumnya.</p>
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
