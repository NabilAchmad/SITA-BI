@extends('layouts.template.mahasiswa')
@section('title', 'Ajukan TA')
@section('content')
    <style>
        .text-primary-donk {
            color: #004085 !important;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">

                        <!-- Tombol Kembali -->
                        <div class="mb-3">
                            <a href="{{ route('tugas-akhir.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>

                        <h3 class="text-center mb-4 text-primary-donk">Ajukan Tugas Akhir</h3>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tugasAkhir.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Tugas Akhir</label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    placeholder="Masukkan judul tugas akhir" required>
                            </div>

                            <div class="mb-3">
                                <label for="abstrak" class="form-label">Abstrak</label>
                                <textarea class="form-control" id="abstrak" name="abstrak" rows="4" placeholder="Masukkan abstrak tugas akhir"
                                    required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    <i class="bi bi-upload"></i> Ajukan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
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
