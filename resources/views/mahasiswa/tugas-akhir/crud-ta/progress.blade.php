@extends('layouts.template.mahasiswa')
@section('title', 'Progress Tugas Akhir')

@push('styles')
    <!-- Custom CSS -->
    <style>
        .transition-hover {
            transition: all 10.5s ease-in;
        }

        .transition-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .progress-label {
            font-size: 0.9rem;
            font-style: italic;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white px-3 py-2 rounded-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('tugas-akhir.dashboard') }}">Dashboard Tugas Akhir</a></li>
            <li class="breadcrumb-item active" aria-current="page">Progres</li>
        </ol>
    </nav>
    <!-- Konten Progres -->
    <div class="container py-3">
        <div class="row justify-content-center">
            @if ($tugasAkhir)
                @php
                    $ta = $tugasAkhir;
                    $statusLabel = match ($ta->status) {
                        'diajukan' => 'Dalam Proses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                        default => 'Tidak Diketahui',
                    };

                    $progress = match ($ta->status) {
                        'diajukan' => 0,
                        'disetujui' => 50,
                        'selesai' => 100,
                        default => 0,
                    };

                    $progressColor = match ($ta->status) {
                        'diajukan' => 'bg-warning',
                        'disetujui' => 'bg-info',
                        'selesai' => 'bg-success',
                        'ditolak' => 'bg-danger',
                        default => 'bg-secondary',
                    };

                    $abstrakLimit = 300;
                    $abstrakFull = $ta->abstrak;
                    $abstrakShort =
                        strlen($abstrakFull) > $abstrakLimit ? substr($abstrakFull, 0, $abstrakLimit) : $abstrakFull;
                    $hasMore = strlen($abstrakFull) > $abstrakLimit;
                @endphp

                <div class="col-12 col-lg-10 col-xl-8">
                    <h2 class="text-primary fw-bold mb-4 text-center">{{ $ta->judul }}</h2>

                    <!-- Status -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-semibold text-secondary">Status</label>
                        <div class="col-sm-9 text-capitalize align-self-center">{{ $statusLabel }}</div>
                    </div>

                    <!-- Tanggal Pengajuan -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-semibold text-secondary">Tanggal Pengajuan</label>
                        <div class="col-sm-9 align-self-center">
                            {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d M Y') }}
                        </div>
                    </div>

                    <!-- Abstrak -->
                    <div class="mb-4">
                        <label class="fw-semibold text-secondary d-block mb-2">Abstrak</label>
                        <p class="fst-italic text-muted" style="white-space: pre-line;"
                            id="abstrak-short-{{ $ta->id }}">
                            {{ $abstrakShort }}{{ $hasMore ? '...' : '' }}
                        </p>
                        @if ($hasMore)
                            <p class="fst-italic text-muted d-none" style="white-space: pre-line;"
                                id="abstrak-full-{{ $ta->id }}">
                                {{ $abstrakFull }}
                            </p>
                            <button class="btn btn-link p-0 text-primary" type="button"
                                onclick="toggleAbstrak({{ $ta->id }})" id="btn-toggle-{{ $ta->id }}">
                                Lihat Selengkapnya
                            </button>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <label class="fw-semibold text-secondary d-block mb-2">Progress</label>
                        <div class="progress rounded-pill" style="height: 24px;">
                            <div class="progress-bar {{ $progressColor }} fw-semibold d-flex justify-content-center align-items-center"
                                role="progressbar" style="width: {{ $progress }}%; transition: width 0.6s ease;"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $progress }}%
                            </div>
                        </div>
                        <div class="progress-label mt-1">
                            Status: <strong>{{ $statusLabel }}</strong>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
                        <a href="{{ asset('storage/' . $ta->file_path) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm rounded-pill px-4 py-2 d-flex align-items-center gap-2 shadow-sm transition-hover">
                            <i class="bi bi-file-earmark-text"></i> Lihat Proposal
                        </a>

                        <button type="button"
                            class="btn btn-sm rounded-pill px-4 py-2 shadow-sm transition-hover {{ $ta->status === 'diajukan' ? 'btn-warning' : 'btn-outline-secondary' }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }} data-bs-toggle="modal"
                            data-bs-target="#revisiModal{{ $ta->id }}">
                            <i class="bi bi-pencil-square"></i> Revisi
                        </button>

                        <button type="button"
                            class="btn btn-danger btn-sm rounded-pill px-4 py-2 shadow-sm transition-hover"
                            data-bs-toggle="collapse" data-bs-target="#cancelForm{{ $ta->id }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }}>
                            <i class="bi bi-x-circle"></i> Batalkan
                        </button>
                    </div>

                    <!-- Form Pembatalan -->
                    <div class="collapse" id="cancelForm{{ $ta->id }}">
                        <form action="{{ route('tugasAkhir.cancelTA', $ta->id) }}" method="POST" class="mb-5">
                            @csrf
                            <div class="mb-3">
                                <label for="alasan" class="form-label fw-semibold text-secondary">Alasan
                                    Pembatalan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">Kirim Pembatalan</button>
                        </form>
                    </div>

                    <!-- Modal Revisi -->
                    <div class="modal fade" id="revisiModal{{ $ta->id }}" tabindex="-1"
                        aria-labelledby="revisiModalLabel{{ $ta->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('tugas-akhir.revisi', $ta->id) }}" method="POST"
                                enctype="multipart/form-data" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-secondary text-white">
                                    <h5 class="modal-title" id="revisiModalLabel{{ $ta->id }}">Revisi Proposal Tugas
                                        Akhir</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-4">
                                        <label for="file_revisi" class="form-label fw-semibold text-secondary">Upload File
                                            Revisi</label>
                                        <input type="file" class="form-control" id="file_revisi" name="file_revisi"
                                            accept=".pdf,.doc,.docx" required>
                                    </div>
                                    <div>
                                        <label for="point_revisi" class="form-label fw-semibold text-secondary">Point
                                            Revisi</label>
                                        <textarea class="form-control" id="point_revisi" name="point_revisi" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer gap-2">
                                    <button type="button" class="btn btn-outline-light btn-rounded-pill px-4"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-rounded-pill px-4">Kirim
                                        Revisi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 text-center">
                    <div class="alert alert-info rounded-3 fs-5 shadow-sm">Belum ada pengajuan tugas akhir.</div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleAbstrak(id) {
            const shortEl = document.getElementById(`abstrak-short-${id}`);
            const fullEl = document.getElementById(`abstrak-full-${id}`);
            const btn = document.getElementById(`btn-toggle-${id}`);

            if (fullEl.classList.contains('d-none')) {
                fullEl.classList.remove('d-none');
                shortEl.classList.add('d-none');
                btn.textContent = 'Tutup';
            } else {
                fullEl.classList.add('d-none');
                shortEl.classList.remove('d-none');
                btn.textContent = 'Lihat Selengkapnya';
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('cancel_success'))
                swal({
                    title: "Pembatalan Berhasil!",
                    text: "{{ session('cancel_success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary"
                        }
                    }
                });
            @endif

            @if (session('revisi_success'))
                swal({
                    title: "Revisi Terkirim!",
                    text: "{{ session('revisi_success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary"
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
