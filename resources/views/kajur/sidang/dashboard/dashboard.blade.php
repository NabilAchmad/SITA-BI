@extends('layouts.template.kajur')

@section('title', 'Dashboard Sidang Kajur')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Sidang Kajur</h1>
            <div class="text-muted">{{ now()->format('l, d F Y') }}</div>
        </div>
        <div class="row g-4">
            <!-- Seminar Proposal Card with Tabs -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Seminar Proposal</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="seminarProposalTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="menunggu-sempro-tab" data-bs-toggle="tab" data-bs-target="#menunggu-sempro" type="button" role="tab" aria-controls="menunggu-sempro" aria-selected="true">
                                    Menunggu Sidang Sempro
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="terjadwal-sempro-tab" data-bs-toggle="tab" data-bs-target="#terjadwal-sempro" type="button" role="tab" aria-controls="terjadwal-sempro" aria-selected="false">
                                    Sempro Terjadwal
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pasca-sempro-tab" data-bs-toggle="tab" data-bs-target="#pasca-sempro" type="button" role="tab" aria-controls="pasca-sempro" aria-selected="false">
                                    Pasca Sidang Sempro
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="seminarProposalTabsContent">
                            <div class="tab-pane fade show active" id="menunggu-sempro" role="tabpanel" aria-labelledby="menunggu-sempro-tab">
                                <a href="{{ route('kajur.sidang.menunggu.sempro') }}" class="text-decoration-none card-hover">
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
                            <div class="tab-pane fade" id="terjadwal-sempro" role="tabpanel" aria-labelledby="terjadwal-sempro-tab">
                                <a href="{{ route('kajur.sidang.jadwal.sempro') }}" class="text-decoration-none card-hover">
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
                            <div class="tab-pane fade" id="pasca-sempro" role="tabpanel" aria-labelledby="pasca-sempro-tab">
                                <a href="{{ route('kajur.sidang.pasca.sempro') }}" class="text-decoration-none card-hover">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidang Akhir Card with Tabs -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Sidang Akhir</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="sidangAkhirTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="menunggu-akhir-tab" data-bs-toggle="tab" data-bs-target="#menunggu-akhir" type="button" role="tab" aria-controls="menunggu-akhir" aria-selected="true">
                                    Menunggu Sidang Akhir
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="terjadwal-akhir-tab" data-bs-toggle="tab" data-bs-target="#terjadwal-akhir" type="button" role="tab" aria-controls="terjadwal-akhir" aria-selected="false">
                                    Sidang Akhir Terjadwal
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pasca-akhir-tab" data-bs-toggle="tab" data-bs-target="#pasca-akhir" type="button" role="tab" aria-controls="pasca-akhir" aria-selected="false">
                                    Pasca Sidang Akhir
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="sidangAkhirTabsContent">
                            <div class="tab-pane fade show active" id="menunggu-akhir" role="tabpanel" aria-labelledby="menunggu-akhir-tab">
                                <a href="{{ route('kajur.sidang.menunggu.akhir') }}" class="text-decoration-none card-hover">
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
                            <div class="tab-pane fade" id="terjadwal-akhir" role="tabpanel" aria-labelledby="terjadwal-akhir-tab">
                                <a href="{{ route('kajur.sidang.jadwal.akhir') }}" class="text-decoration-none card-hover">
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
                            <div class="tab-pane fade" id="pasca-akhir" role="tabpanel" aria-labelledby="pasca-akhir-tab">
                                <a href="{{ route('kajur.sidang.pasca.akhir') }}" class="text-decoration-none card-hover">
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
                </div>
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
