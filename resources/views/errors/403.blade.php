@extends('layouts.template.main')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="container py-5">
        <div class="text-center">
            <h1 class="display-4 text-danger">403</h1>
            <h2 class="mb-4">Akses Ditolak</h2>
            <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Mungkin tugas akhir Anda telah dibatalkan atau sesi Anda sudah tidak berlaku.</p>
            <a href="{{ route('dashboard.mahasiswa') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
