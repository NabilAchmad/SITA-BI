@extends('layouts.template.mahasiswa')
@section('title', 'Ajukan TA')
@section('content')

    <!-- Page Header with Back Button (moved to top-right) -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="text-primary-donk">Ajukan Tugas Akhir</h2>
        <a href="{{ route('tugas-akhir.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Container with max-width -->
    <div class="mx-auto" style="max-width: 800px;">
        <form action="{{ route('tugasAkhir.store') }}" method="POST" enctype="multipart/form-data"
            class="p-4 bg-white rounded-4 shadow-sm">
            @csrf

            <!-- Judul Field -->
            <div class="mb-4">
                <label for="judul" class="form-label fw-semibold">Judul Tugas Akhir</label>
                <input type="text" class="form-control form-control-lg" id="judul" name="judul"
                    placeholder="Masukkan judul tugas akhir" required>
            </div>

            <!-- Abstrak Field -->
            <div class="mb-4">
                <label for="abstrak" class="form-label fw-semibold">Abstrak</label>
                <textarea class="form-control" id="abstrak" name="abstrak" rows="6" placeholder="Masukkan abstrak tugas akhir"
                    required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-5">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="bi bi-upload me-2"></i> Ajukan
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if ($errors->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $errors->first('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endpush

@push('styles')
    <style>
        .text-primary-donk {
            color: #004085 !important;
        }
    </style>
@endpush
