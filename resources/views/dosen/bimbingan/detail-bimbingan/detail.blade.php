@extends('layouts.template.main')

@section('title', 'Detail Mahasiswa Bimbingan')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-gradient">
                    <i class="bi bi-person-badge me-2"></i>Detail Mahasiswa Bimbingan
                </h2>
            </div>
        </div>

        {{-- Informasi Mahasiswa --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('storage/' . ($mahasiswa->user->photo ?? 'default-avatar.jpg')) }}"
                            class="rounded-circle shadow-sm img-fluid"
                            style="width: 100px; height: 100px; object-fit: cover;" alt="Foto Mahasiswa">
                    </div>
                    <div class="col-md-5">
                        <h4 class="fw-bold mb-0">{{ $mahasiswa->user->name }}</h4>
                        <span class="badge bg-primary bg-opacity-10 text-white mt-1">{{ $mahasiswa->nim }}</span>
                        <ul class="list-unstyled mt-3">
                            <li><i class="bi bi-building me-2 text-black"></i><strong>Prodi:</strong>
                                {{ strtoupper($mahasiswa->prodi) }}</li>
                            <li><i class="bi bi-calendar me-2 text-black"></i><strong>Angkatan:</strong>
                                {{ $mahasiswa->angkatan }}</li>
                            <li><i class="bi bi-person-check me-2 text-black"></i><strong>Status:</strong>
                                {{ ucfirst($mahasiswa->status) }}</li>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <div class="bg-light p-3 rounded-3 h-100">
                            <h6 class="fw-bold mb-3">Kontak Mahasiswa</h6>
                            <p class="mb-1"><i
                                    class="bi bi-envelope me-2 text-muted"></i>{{ $mahasiswa->user->email ?? '-' }}</p>
                            <p class="mb-1"><i
                                    class="bi bi-telephone me-2 text-muted"></i>{{ $mahasiswa->no_telepon ?? '-' }}</p>
                            <p class="mb-0"><i class="bi bi-house me-2 text-muted"></i>{{ $mahasiswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Tugas Akhir --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-secondary mb-0">
                            <i class="bi bi-journal-bookmark me-2"></i>Progres Tugas Akhir
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $tugasAkhir->judul ?? 'Judul Belum Ditentukan' }}</h4>
                        <span class="badge bg-info bg-opacity-10 text-white mb-3 py-2 px-3 rounded-pill">
                            {{ ucfirst(str_replace('_', ' ', $tugasAkhir->status)) }}
                        </span>
                        <p class="mb-2"><strong>Tanggal Pengajuan:</strong> {{ $tugasAkhir->tanggal_pengajuan ?? '-' }}
                        </p>

                        <div class="mb-3">
                            <h6 class="fw-bold">Abstrak</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $tugasAkhir->abstrak ?? 'Belum ada abstrak.' }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Dokumen Proposal</h6>
                            @if ($tugasAkhir->file_path)
                                <a href="{{ asset('storage/' . $tugasAkhir->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-download me-1"></i> Unduh Proposal
                                </a>
                            @else
                                <span class="badge bg-light text-muted py-2 px-3">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Riwayat Revisi --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-warning mb-0">
                            <i class="bi bi-clipboard2-pulse me-2"></i>Riwayat Revisi
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse ($revisiList as $revisi)
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-0">Revisi ke-{{ $loop->iteration }}</h6>
                                    <small class="text-muted">{{ $revisi->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <div class="bg-light-warning p-3 rounded mt-2">
                                    <p class="mb-2">{{ $revisi->deskripsi }}</p>
                                    @if ($revisi->file_path)
                                        <a href="{{ asset('storage/' . $revisi->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-warning rounded-pill">
                                            <i class="bi bi-download me-1"></i> Unduh Revisi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                <p class="text-muted">Tidak ada revisi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Riwayat Bimbingan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-success mb-0">
                            <i class="bi bi-calendar2-check me-2"></i>Riwayat Bimbingan
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse ($bimbinganList as $bimbingan)
                            <div class="mb-4">
                                <h6 class="fw-bold">
                                    {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d M Y') }} •
                                    {{ $bimbingan->jam_bimbingan }}
                                </h6>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success mb-2">{{ $bimbingan->status_bimbingan }}</span>
                                <p class="mb-2">{{ $bimbingan->catatan ?? 'Tidak ada catatan' }}</p>
                                @if ($bimbingan->file_path)
                                    <a href="{{ asset('storage/' . $bimbingan->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill">
                                        <i class="bi bi-download me-1"></i> Dokumen Bimbingan
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted fs-2"></i>
                                <p class="text-muted">Belum ada bimbingan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .text-gradient {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endpush
