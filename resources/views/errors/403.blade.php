@extends('layouts.template.main')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="container py-5">
        <div class="text-center">
            <h1 class="display-4 text-danger">403</h1>
            <h2 class="mb-4">Akses Ditolak</h2>
            <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Mungkin tugas akhir Anda telah dibatalkan atau sesi Anda sudah tidak berlaku.</p>

            @php
                $user = auth()->user();
                // Ambil role pertama dari user (Spatie getRoleNames() mengembalikan collection)
                $role = $user->getRoleNames()->first() ?? 'mahasiswa';
                $routeName = $role . '.dashboard';

                // Pastikan route ada, fallback ke mahasiswa.dashboard jika tidak
                $dashboardUrl = \Illuminate\Support\Facades\Route::has($routeName)
                    ? route($routeName)
                    : route('mahasiswa.dashboard');
            @endphp

            <a href="{{ $dashboardUrl }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
