@extends('layouts.template.main')

@section('title', 'Verifikasi Pendaftaran Sidang')

@section('content')
    <div class="container-fluid px-4">
        {{-- Header Halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-0">
                    <i class="bi bi-file-earmark-check-fill me-2"></i>Verifikasi Berkas Sidang
                </h2>
                <p class="text-muted mb-0">Periksa dan verifikasi kelengkapan berkas mahasiswa</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Status Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Loop untuk setiap pendaftaran yang menunggu persetujuan --}}
        @forelse($pendaftaranList as $pendaftaran)
            {{-- Informasi Mahasiswa & TA --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle me-2 text-white"></i>Informasi Mahasiswa
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light"
                                    value="{{ $pendaftaran->tugasAkhir?->mahasiswa?->user?->name ?? 'N/A' }}" readonly>
                                <label class="fw-semibold text-primary">
                                    <i class="bi bi-person-fill me-1"></i>Nama Mahasiswa
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light"
                                    value="{{ $pendaftaran->tugasAkhir?->mahasiswa?->nim ?? 'N/A' }}" readonly>
                                <label class="fw-semibold text-primary">
                                    <i class="bi bi-credit-card-2-front-fill me-1"></i>NIM
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control border-0 bg-light" style="height: 80px" readonly>{{ $pendaftaran->tugasAkhir?->judul ?? 'Judul Belum Ada' }}</textarea>
                                <label class="fw-semibold text-primary">
                                    <i class="bi bi-journal-text me-1"></i>Judul Tugas Akhir
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Berkas yang Diunggah --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient bg-info text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder-check me-2 text-white"></i>Berkas Persyaratan
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($pendaftaran->files as $index => $file)
                        <div class="list-group-item border-0 py-3 px-4 {{ $index % 2 == 0 ? 'bg-light' : 'bg-white' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-file-earmark-text-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">
                                            {{ Str::title(str_replace('_', ' ', $file->file_type)) }}
                                        </h6>
                                        <small class="text-muted">
                                            <i
                                                class="bi bi-file-earmark me-1"></i>{{ Str::limit($file->original_name, 40) }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $file->file_path) }}"
                                        download="{{ $file->original_name }}" class="btn btn-primary btn-sm rounded-pill">
                                        <i class="bi bi-download me-1"></i>Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-folder-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted">Tidak Ada Berkas</h6>
                            <p class="text-muted mb-0">Mahasiswa belum mengunggah berkas persyaratan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Panel Aksi Dosen --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient bg-secondary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-check2-square me-2 text-white"></i>Tindakan Verifikasi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-check text-primary mb-3" style="font-size: 3rem;"></i>
                        <h6 class="fw-bold text-dark">Keputusan Verifikasi</h6>
                        <p class="text-muted">Setelah memeriksa semua berkas, silakan berikan keputusan Anda.</p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-center gap-3">
                                {{-- Tombol Tolak (Membuka Modal) --}}
                                <button type="button"
                                    class="btn btn-danger btn-lg rounded-pill px-4 py-2 shadow-sm flex-fill"
                                    data-bs-toggle="modal" data-bs-target="#modalTolak{{ $pendaftaran->id }}">
                                    <i class="bi bi-x-circle-fill me-2"></i>Tolak Pendaftaran
                                </button>

                                {{-- Tombol Terima (Langsung Submit Form) --}}
                                {{-- Pastikan route 'dosen.verifikasi-sidang.approve' benar --}}
                                <form action="{{ route('dosen.verifikasi-sidang.approve', $pendaftaran->id) }}"
                                    method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success btn-lg rounded-pill px-4 py-2 shadow-sm w-100">
                                        <i class="bi bi-check-circle-fill me-2"></i>Terima Pendaftaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal untuk Alasan Penolakan --}}
            <div class="modal fade" id="modalTolak{{ $pendaftaran->id }}" tabindex="-1"
                aria-labelledby="modalTolakLabel{{ $pendaftaran->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold" id="modalTolakLabel{{ $pendaftaran->id }}">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-white"></i>Form Penolakan Pendaftaran
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        {{-- Pastikan route 'dosen.verifikasi-sidang.reject' benar --}}
                        <form action="{{ route('dosen.verifikasi-sidang.reject', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="alert alert-warning rounded-4" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>Perhatian!</strong> Catatan penolakan ini akan terlihat oleh mahasiswa. Berikan
                                    alasan
                                    yang jelas dan konstruktif.
                                </div>

                                <div class="mb-3">
                                    <label for="catatan" class="form-label fw-semibold">
                                        <i class="bi bi-chat-left-text me-1"></i>Catatan Penolakan / Revisi
                                    </label>
                                    {{-- Nama input diperbaiki dari 'catatan_revisi' menjadi 'catatan' agar cocok dengan controller --}}
                                    <textarea class="form-control border-2 border-danger" name="catatan" id="catatan" rows="6"
                                        placeholder="Contoh: Skor TOEIC belum memenuhi syarat minimal (skor 400), harap unggah ulang sertifikat yang sesuai dengan ketentuan yang berlaku."
                                        required style="resize: vertical;"></textarea>
                                    <div class="form-text">
                                        <i class="bi bi-lightbulb me-1"></i>
                                        Berikan catatan yang spesifik dan membantu mahasiswa untuk melakukan perbaikan.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill">
                                    <i class="bi bi-send-fill me-1"></i>Kirim Penolakan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Pemisah antar pendaftaran jika ada lebih dari satu --}}
            @if (!$loop->last)
                <hr class="my-5 border-2">
            @endif

        @empty
            {{-- Tampilan jika tidak ada pendaftaran yang perlu diverifikasi --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-cloud-drizzle text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-bold text-primary">Tidak Ada Pendaftaran</h4>
                    <p class="text-muted mb-0">Saat ini tidak ada pendaftaran sidang yang memerlukan verifikasi dari Anda.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary) 100%);
        }

        .bg-gradient.bg-info {
            background: linear-gradient(135deg, var(--bs-info) 0%, var(--bs-info) 100%);
        }

        .bg-gradient.bg-secondary {
            background: linear-gradient(135deg, var(--bs-secondary) 0%, var(--bs-secondary) 100%);
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .list-group-item {
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: var(--bs-light) !important;
        }
    </style>
@endpush
