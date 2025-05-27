@extends('layouts.template.kaprodi')

@section('title', 'Dashboard Sidang')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4 font-weight-bold">Dashboard Sidang</h2>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3 shadow-lg hover-card">
                <div class="card-header bg-primary-dark">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Jadwal Sidang Akhir
                </div>
                <div class="card-body">
                    <h5 class="card-title display-4">{{ $jadwalCount ?? 0 }}</h5>
                    <p class="card-text">Jumlah jadwal sidang yang telah dijadwalkan.</p>
                    <a href="{{ route('kaprodi.jadwal') }}" class="btn btn-light btn-lg w-100">
                        <i class="fas fa-arrow-right"></i> Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3 shadow-lg hover-card">
                <div class="card-header bg-success-dark">
                    <i class="fas fa-user-graduate me-2"></i>
                    Jadwal Sidang Sempro
                </div>
                <div class="card-body">
                    <h5 class="card-title display-4">{{ $mahasiswaCount ?? 0 }}</h5>
                    <p class="card-text">Jumlah mahasiswa yang mengikuti sidang.</p>
                    {{-- <a href="{{ route('kaprodi.jadwal') }}" class="btn btn-light btn-lg w-100">
                        {{-- <i class="fas fa-arrow-right"></i> Lihat Mahasiswa --}}
                    {{-- </a> --}} 
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: scale(1.03);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.bg-primary-dark {
    background-color: rgba(0,0,0,0.1);
    border: none;
}
.bg-success-dark {
    background-color: rgba(0,0,0,0.1);
    border: none;
}
.card-title {
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}
.card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}
.btn {
    border-radius: 5px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.2s ease;
}
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.card-header {
    font-weight: 600;
    padding: 15px 20px;
}
.card-body {
    padding: 20px;
}
.card-text {
    font-size: 1rem;
    margin-bottom: 20px;
}
</style>
@endsection