@extends('layouts.template.main')
@section('title', 'Ajukan TA')
@section('content')
    <!-- Page Header with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Ajukan Tugas Akhir</h2>
        <a href="{{ route('tugas-akhir.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('tugasAkhir.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Judul Field -->
                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold text-secondary">Judul Tugas Akhir</label>
                    <input type="text" class="form-control" id="judul" name="judul"
                        placeholder="Masukkan judul tugas akhir" required>
                </div>

                <!-- Submit Button -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-send me-1"></i> Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .text-primary-donk {
            color: #004085 !important;
        }
    </style>
@endpush
