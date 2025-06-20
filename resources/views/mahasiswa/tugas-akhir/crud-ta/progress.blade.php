@extends('layouts.template.mahasiswa')
@section('title', 'Progress Tugas Akhir')

@push('styles')
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .progress-animated {
            background-size: 1rem 1rem;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
            animation: progress-bar-stripes 1s linear infinite;
        }

        .icon-box {
            width: 60px;
            height: 60px;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('tugas-akhir.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active">Progress Tugas Akhir</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 fw-bold text-dark">Progress Tugas Akhir</h1>
            <p class="text-muted">Pantau perkembangan dan status tugas akhir Anda</p>
        </div>
    </div>

    @if ($pembimbingList->count())
        <!-- Dosen Pembimbing -->
        <div class="card border-0 shadow-sm mb-4 card-hover">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 48px; height: 48px; background-color: rgba(13,110,253,0.1);">
                            <i class="bi bi-people-fill text-primary fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Dosen Pembimbing</h5>
                        <small class="text-muted">Tim pembimbing tugas akhir Anda</small>
                    </div>
                </div>

                <!-- List Pembimbing -->
                <div class="row g-3">
                    @foreach ($pembimbingList as $pembimbing)
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100 shadow-sm rounded-3">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary mb-1 text-capitalize">{{ $pembimbing->peran }}</span>
                                        <h6 class="mb-0 fw-semibold">{{ $pembimbing->dosen->user->name ?? '-' }}</h6>
                                        <small class="text-muted d-block">Dosen {{ ucfirst($pembimbing->peran) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    @if (!$tugasAkhir)
        <!-- No Data State -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg card-hover">
                    <div class="card-body text-center p-5">
                        <div
                            class="icon-box mx-auto mb-4 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt text-primary fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Belum Ada Data Tugas Akhir</h3>
                        <p class="text-muted mb-4">Anda belum mengajukan atau belum memiliki data Tugas Akhir saat ini.
                            Mulai perjalanan akademik Anda sekarang!</p>
                        <a href="{{ route('tugas-akhir.ajukan') }}" class="btn btn-gradient btn-lg rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i>Ajukan Tugas Akhir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php
            $ta = $tugasAkhir;
            $statusMap = [
                'diajukan' => 'Dalam Proses',
                'draft' => 'Draft',
                'revisi' => 'Revisi',
                'disetujui' => 'Disetujui',
                'lulus_tanpa_revisi' => 'Lulus Tanpa Revisi',
                'lulus_dengan_revisi' => 'Lulus Dengan Revisi',
                'ditolak' => 'Ditolak',
                'dibatalkan' => 'Dibatalkan',
                'menunggu_pembatalan' => 'Menunggu Persetujuan Pembatalan',
            ];
            $progress = match ($ta->status) {
                'diajukan' => 5,
                'disetujui' => 10,
                'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 100,
                default => 10,
            };
            $progressColor = match ($ta->status) {
                'diajukan' => 'warning',
                'disetujui' => 'info',
                'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 'success',
                'ditolak' => 'danger',
                default => 'secondary',
            };
            $abstrakLimit = 300;
            $abstrakFull = $ta->abstrak;
            $abstrakShort =
                strlen($abstrakFull) > $abstrakLimit ? substr($abstrakFull, 0, $abstrakLimit) : $abstrakFull;
            $hasMore = strlen($abstrakFull) > $abstrakLimit;
        @endphp

        <!-- Header Card -->
        <div class="card border-0 shadow-lg mb-4 card-hover">
            <div class="card-header gradient-primary text-white border-0 rounded-top-3">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">{{ $ta->judul }}</h2>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-calendar text-white"></i>
                            Diajukan pada {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d F Y') }}
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <span class="badge bg-white text-primary fs-6 px-3 py-2">
                            <i class="fas fa-flag me-1"></i>{{ $statusMap[$ta->status] ?? 'Tidak Diketahui' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Progress Section -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0">Progress Tugas Akhir</h5>
                        <span class="badge bg-primary fs-6">{{ $progress }}%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-{{ $progressColor }} progress-animated" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div
                                    class="icon-box mx-auto mb-2 bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <h6 class="fw-bold">Tanggal Pengajuan</h6>
                                <p class="text-muted mb-0 small">
                                    {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div
                                    class="icon-box mx-auto mb-2 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <h6 class="fw-bold">Status Saat Ini</h6>
                                <p class="text-muted mb-0 small">{{ $statusMap[$ta->status] ?? 'Tidak Diketahui' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div
                                    class="icon-box mx-auto mb-2 bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tachometer-alt text-white"></i>
                                </div>
                                <h6 class="fw-bold">Progress</h6>
                                <p class="text-muted mb-0 small">{{ $progress }}% Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abstrak Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3"
                            style="width: 45px; height: 45px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="fas fa-file-alt text-primary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Abstrak</h5>
                            <small class="text-muted">Ringkasan proposal tugas akhir</small>
                        </div>
                    </div>

                    <div class="card border-0 bg-light shadow-sm rounded-3">
                        <div class="card-body">
                            <p class="mb-0" id="abstrak-short-{{ $ta->id }}" style="white-space: pre-line;">
                                {{ $abstrakShort }}{{ $hasMore ? '...' : '' }}
                            </p>

                            @if ($hasMore)
                                <p class="mb-0 d-none" id="abstrak-full-{{ $ta->id }}"
                                    style="white-space: pre-line;">
                                    {{ $abstrakFull }}
                                </p>
                                <button class="btn btn-sm btn-link text-decoration-none text-primary mt-2 ps-0"
                                    type="button" onclick="toggleAbstrak({{ $ta->id }})"
                                    id="btn-toggle-{{ $ta->id }}">
                                    <i class="fas fa-chevron-down me-1"></i> Lihat Selengkapnya
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if ($isMengajukanTA && $ta->status === 'disetujui')
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ asset('storage/' . $ta->file_path) }}" target="_blank"
                            class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-file-pdf me-2"></i>Lihat Proposal
                        </a>
                        <button type="button" class="btn btn-outline-warning rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#revisiModal{{ $ta->id }}">
                            <i class="fas fa-edit me-2"></i>Revisi Proposal
                        </button>
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="collapse"
                            data-bs-target="#cancelForm{{ $ta->id }}">
                            <i class="fas fa-times-circle me-2"></i>Batalkan TA
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Pembatalan -->
        @if ($isMengajukanTA && $ta->status === 'disetujui')
            <div class="collapse" id="cancelForm{{ $ta->id }}">
                <div class="card border-0 shadow-sm mb-4 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 me-3"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-exclamation-triangle text-white fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-danger mb-1">Form Pembatalan</h5>
                                <small class="text-muted">Silakan isi alasan pembatalan tugas akhir Anda secara
                                    lengkap</small>
                            </div>
                        </div>

                        <form action="{{ route('tugasAkhir.cancelTA', $ta->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="alasan" class="form-label fw-semibold">Alasan Pembatalan</label>
                                <textarea class="form-control rounded-3" id="alasan" name="alasan" rows="4"
                                    placeholder="Jelaskan alasan pembatalan tugas akhir Anda..." required></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2"
                                    data-bs-toggle="collapse" data-bs-target="#cancelForm{{ $ta->id }}">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pembatalan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Revisi -->
        @if ($isMengajukanTA && $ta->status === 'disetujui')
            <div class="modal fade" id="revisiModal{{ $ta->id }}" tabindex="-1"
                aria-labelledby="revisiModalLabel{{ $ta->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 overflow-hidden">
                        <form action="{{ route('tugas-akhir.revisi', $ta->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Modal Header -->
                            <div class="modal-header gradient-primary text-white">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 45px; height: 45px;">
                                        <i class="fas fa-edit text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="modal-title fw-bold mb-0" id="revisiModalLabel{{ $ta->id }}">
                                            Revisi Proposal
                                        </h5>
                                        <small class="opacity-75">Upload file revisi dan catatan perubahan</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body px-4 py-3">
                                <div class="mb-3">
                                    <label for="file_revisi" class="form-label fw-semibold">
                                        <i class="fas fa-cloud-upload-alt text-primary me-1"></i>File Revisi
                                    </label>
                                    <input type="file" class="form-control" id="file_revisi" name="file_revisi"
                                        accept=".pdf,.doc,.docx" required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Format: PDF, DOC, DOCX (maksimal 5MB)
                                    </div>
                                </div>

                                <div>
                                    <label for="point_revisi" class="form-label fw-semibold">
                                        <i class="fas fa-list-ul text-primary me-1"></i>Point Revisi
                                    </label>
                                    <textarea class="form-control" id="point_revisi" name="point_revisi" rows="5"
                                        placeholder="Jelaskan poin-poin revisi yang telah dilakukan..." required></textarea>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer bg-light border-top-0">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Tutup
                                </button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-check me-1"></i>Simpan Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    @endif
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
                btn.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Tutup';
            } else {
                fullEl.classList.add('d-none');
                shortEl.classList.remove('d-none');
                btn.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Lihat Selengkapnya';
            }
        }

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
