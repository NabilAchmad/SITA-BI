@extends('layouts.template.mahasiswa')
@section('title', 'Tugas Akhir Dibatalkan')
@section('content')
    <div class="container">
        <div class="text-center text-danger mb-5">
            <h2 class="fw-bold">Tugas Akhir yang Ditolak</h2>
            <p class="text-muted">Daftar pengajuan tugas akhir yang ditolak dan dibatalkan oleh mahasiswa.</p>
        </div>

        <div class="row justify-content-center">
            @foreach ($tugasAkhirDibatalkan as $ta)
                <div class="col-lg-8 mb-4">
                    <div class="card border border-danger shadow-sm rounded-4">
                        <div class="card-header bg-danger text-white text-center rounded-top-4">
                            <h5 class="mb-0">{{ $ta->judul }}</h5>
                        </div>
                        <div class="card-body p-4">
                            <p><strong>Status:</strong> <span class="text-danger">Dibatalkan</span></p>
                            <p><strong>Tanggal Pengajuan:</strong>
                                {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d M Y') }}</p>
                            <p><strong>Abstrak:</strong><br>
                                <span class="text-muted">{{ $ta->abstrak }}</span>
                            </p>
                            <p><strong>Alasan Pembatalan:</strong><br>
                                <span class="text-muted">{{ $ta->alasan_pembatalan }}</span>
                            </p>
                        </div>
                        <div class="card-footer bg-light text-end rounded-bottom-4">
                            <button class="btn btn-outline-secondary rounded-pill px-4" disabled>Telah Dibatalkan</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
