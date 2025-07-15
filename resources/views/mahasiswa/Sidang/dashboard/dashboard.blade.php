@extends('layouts.template.main')

@section('title', 'Dashboard Sidang')

@section('content')
<div class="container-fluid py-4">

    <div class="position-relative overflow-hidden rounded-3 mb-4 p-4" style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
        <div class="position-relative z-1">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Sidang
            </h4>
            <p class="text-muted mb-0">Kelola seluruh proses sidang dari pendaftaran hingga pemantauan nilai dan jadwal.</p>
        </div>
        <i class="fas fa-clipboard-check text-primary position-absolute opacity-10" style="font-size: 5rem; right: 1.5rem; bottom: 1rem;"></i>
    </div>

    <ul class="nav nav-tabs nav-fill mb-4" id="sidangTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="jadwal-sidang-tab" data-bs-toggle="tab" data-bs-target="#jadwal-sidang" type="button" role="tab" aria-controls="jadwal-sidang" aria-selected="true">
                <i class="fas fa-calendar-alt me-2"></i>Jadwal Sidang
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="nilai-sidang-tab" data-bs-toggle="tab" data-bs-target="#nilai-sidang" type="button" role="tab" aria-controls="nilai-sidang" aria-selected="false">
                <i class="fas fa-star-half-alt me-2"></i>Nilai Sidang
            </button>
        </li>
        {{-- Uncomment this section if you want to add the registration tab later --}}
        {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="daftar-sidang-tab" data-bs-toggle="tab" data-bs-target="#daftar-sidang" type="button" role="tab" aria-controls="daftar-sidang" aria-selected="false">
                <i class="fas fa-file-signature me-2"></i>Daftar Sidang
            </button>
        </li> --}}
    </ul>

    <div class="tab-content" id="sidangTabContent">

        <div class="tab-pane fade show active" id="jadwal-sidang" role="tabpanel" aria-labelledby="jadwal-sidang-tab">
            <div class="card border-light-subtle shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold text-dark mb-3"><i class="fas fa-calendar-check me-2 text-warning"></i>Informasi Jadwal Sidang</h5>
                    <p class="card-text text-muted">
                        Di sini Anda dapat memantau waktu dan lokasi pelaksanaan sidang Anda. Informasi akan diperbarui oleh administrator setelah pendaftaran Anda diverifikasi dan dijadwalkan.
                    </p>
                    <div class="alert alert-warning mt-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Saat ini belum ada jadwal sidang yang tersedia untuk Anda.
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="nilai-sidang" role="tabpanel" aria-labelledby="nilai-sidang-tab">
            <div class="card border-light-subtle shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold text-dark mb-3"><i class="fas fa-award me-2 text-success"></i>Hasil Penilaian Sidang</h5>
                    <p class="card-text text-muted">
                        Lihat hasil penilaian sidang Anda dari para dosen penguji. Nilai akan tersedia setelah sidang selesai dilaksanakan dan dinilai oleh seluruh penguji.
                    </p>
                    <div class="alert alert-success mt-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Belum ada data nilai yang dipublikasikan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Uncomment this section if you want to add the registration tab content later --}}
        {{-- <div class="tab-pane fade" id="daftar-sidang" role="tabpanel" aria-labelledby="daftar-sidang-tab">
            <div class="card border-light-subtle shadow-sm">
                <div class="card-body p-4">
                     <h5 class="card-title fw-semibold text-dark mb-3"><i class="fas fa-file-signature me-2 text-primary"></i>Pendaftaran Sidang Akhir</h5>
                    <p class="card-text text-muted">
                        Lengkapi formulir dan unggah dokumen persyaratan untuk mengajukan permohonan sidang akhir.
                    </p>
                    <a href="#" class="btn btn-primary mt-3">
                        Mulai Pendaftaran <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div> --}}

    </div>

</div>

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
@endsection



{{-- @extends('layouts.template.main')

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
@endsection --}}
