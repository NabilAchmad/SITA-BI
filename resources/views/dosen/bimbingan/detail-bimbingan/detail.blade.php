@extends('layouts.template.main')

@section('title', 'Detail Bimbingan Mahasiswa')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-gradient">
                    <i class="bi bi-person-badge me-2"></i>Detail Bimbingan
                </h2>
            </div>
            <a href="{{ route('dosen.bimbingan.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('storage/' . ($mahasiswa->user->photo ?? 'default-avatar.jpg')) }}"
                            class="rounded-circle shadow-sm img-fluid"
                            style="width: 100px; height: 100px; object-fit: cover;" alt="Foto Mahasiswa">
                    </div>
                    <div class="col-md-10">
                        <h4 class="fw-bold mb-1">{{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})</h4>
                        <p class="text-muted mb-2">
                            {{ strtoupper($mahasiswa->prodi) }} Angkatan {{ $mahasiswa->angkatan }}
                        </p>
                        <h5 class="fw-semibold mt-3">"{{ $tugasAkhir->judul ?? 'Judul Belum Ditentukan' }}"</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Kolom Kiri: Log Bimbingan Terpusat --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-chat-left-dots-fill me-2 text-primary"></i>Log Bimbingan
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Di sini kita akan memuat partial untuk log --}}
                        @include('dosen.bimbingan._log_bimbingan', [
                            'catatanList' => $catatanList, // Anda perlu mengirimkan variabel ini dari controller
                            'tugasAkhir' => $tugasAkhir,
                        ])
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Panel Status & Aksi --}}
            <div class="col-lg-4">
                {{-- Di sini kita akan memuat partial untuk panel aksi --}}
                @include('dosen.bimbingan._panel_aksi', [
                    'tugasAkhir' => $tugasAkhir,
                    'bimbinganCount' => $bimbinganCount, // Anda perlu mengirimkan variabel ini dari controller
                ])
            </div>
        </div>
    </div>

    {{-- ✅ PERBAIKAN: Panggil partial modal di sini --}}
    @include('dosen.bimbingan.partials._modal_jadwal', [
        'tugasAkhir' => $tugasAkhir,
        'mahasiswa' => $mahasiswa,
    ])


@endsection

@push('styles')
    <style>
        .text-gradient {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .log-item {
            position: relative;
            padding-left: 30px;
            border-left: 2px solid #e9ecef;
        }

        .log-icon {
            position: absolute;
            left: -13px;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
