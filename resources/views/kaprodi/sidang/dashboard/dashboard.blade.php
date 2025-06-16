@extends('layouts.template.kaprodi')

@section('title', 'Dashboard Sidang Kaprodi')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Dashboard Sidang Kaprodi</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.menunggu.sempro') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title">Mahasiswa Menunggu Sidang Sempro</h5>
                            <p class="card-text display-4">{{ $waitingSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.menunggu.akhir') }}" class="text-decoration-none">
                    <div class="card text-white bg-secondary h-100">
                        <div class="card-body">
                            <h5 class="card-title">Mahasiswa Menunggu Sidang Akhir</h5>
                            <p class="card-text display-4">{{ $waitingAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.jadwal.sempro') }}" class="text-decoration-none">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">Sidang Sempro Terjadwal</h5>
                            <p class="card-text display-4">{{ $scheduledSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.jadwal.akhir') }}" class="text-decoration-none">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body">
                            <h5 class="card-title">Sidang Akhir Terjadwal</h5>
                            <p class="card-text display-4">{{ $scheduledAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.pasca.sempro') }}" class="text-decoration-none">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pasca Sidang Sempro</h5>
                            <p class="card-text display-4">{{ $pascaSemproCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kaprodi.sidang.pasca.akhir') }}" class="text-decoration-none">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pasca Sidang Akhir</h5>
                            <p class="card-text display-4">{{ $pascaAkhirCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
