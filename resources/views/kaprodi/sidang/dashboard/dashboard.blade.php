@extends('layouts.template.kaprodi')

@section('title', 'Dashboard Sidang Kaprodi')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Sidang Kaprodi</h1>
            <div class="text-muted">{{ now()->format('l, d F Y') }}</div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.menunggu.sempro') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-primary h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-clock me-2"></i>Menunggu Sidang Sempro</h5>
                                <i class="fas fa-users fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $waitingSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.menunggu.akhir') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-secondary h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-hourglass-half me-2"></i>Menunggu Sidang Akhir</h5>
                                <i class="fas fa-user-graduate fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $waitingAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.jadwal.sempro') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-success h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Sempro Terjadwal</h5>
                                <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $scheduledSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.jadwal.akhir') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-danger h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-calendar-day me-2"></i>Sidang Akhir Terjadwal</h5>
                                <i class="fas fa-tasks fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $scheduledAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.pasca.sempro') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-warning h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-check-circle me-2"></i>Pasca Sidang Sempro</h5>
                                <i class="fas fa-file-alt fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $pascaSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.pasca.akhir') }}" class="text-decoration-none card-hover">
                    <div class="card text-white bg-info h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><i class="fas fa-flag-checkered me-2"></i>Pasca Sidang Akhir</h5>
                                <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                            </div>
                            <p class="card-text display-4 mt-3 mb-0 text-center">{{ $pascaAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .card-hover:hover .card {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .card {
            transition: transform 0.3s ease;
        }
        .opacity-50 {
            opacity: 0.5;
        }
    </style>
@endsection