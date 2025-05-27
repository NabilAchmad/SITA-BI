@extends('layouts.template.mahasiswa')
@section('title', 'Progress Tugas Akhir')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white px-3 py-2 rounded-3">
        <li class="breadcrumb-item">
            <a href="{{ route('tugas-akhir.dashboard') }}">Dashboard Tugas Akhir</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Progres</li>
    </ol>
</nav>

<div class="container-fluid py-3">
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
            @endphp

            <div class="col-12 mb-5">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0 fw-semibold">{{ $ta->judul }}</h4>
                    </div>
                    <div class="card-body px-4 py-4">
                        <p class="mb-2"><strong>Status:</strong> <span
                                class="text-capitalize">{{ $statusLabel }}</span></p>
                        <p class="mb-2"><strong>Tanggal Pengajuan:</strong>
                            {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d M Y') }}</p>
                        <p class="mb-3"><strong>Abstrak:</strong><br>
                            <span class="text-muted fst-italic"
                                style="white-space: pre-line;">{{ $ta->abstrak }}</span>
                        </p>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Progress</label>
                            <div class="progress rounded-pill" style="height: 26px;">
                                <div class="progress-bar {{ $progressColor }} fw-bold fs-6" role="progressbar"
                                    style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ $progress }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="card-footer bg-white d-flex flex-wrap gap-3 justify-content-center justify-content-sm-between align-items-center px-4 py-3">
                        <a href="{{ asset('storage/' . $ta->file_path) }}" target="_blank"
                            class="btn btn-outline-primary btn-rounded-pill px-4 py-2">
                            Lihat Proposal
                        </a>

                        <button type="button"
                            class="btn btn-rounded-pill px-4 py-2 {{ $ta->status === 'diajukan' ? 'btn-warning' : 'btn-secondary' }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }} data-bs-toggle="modal"
                            data-bs-target="#revisiModal{{ $ta->id }}">
                            Revisi
                        </button>

                        <button type="button" class="btn btn-danger btn-rounded-pill px-4 py-2"
                            data-bs-toggle="collapse" data-bs-target="#cancelForm{{ $ta->id }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }}>
                            Batalkan
                        </button>
                    </div>

                    <!-- Form Pembatalan Tugas Akhir -->
                    <div class="collapse" id="cancelForm{{ $ta->id }}">
                        <div class="card-body border-top px-4 py-4">
                            <form action="{{ route('tugasAkhir.cancelTA', $ta->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="alasan" class="form-label fw-semibold">Alasan Pembatalan</label>
                                    <textarea class="form-control" id="alasan" name="alasan" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-rounded-pill px-4">Kirim
                                    Pembatalan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Revisi -->
                <div class="modal fade" id="revisiModal{{ $ta->id }}" tabindex="-1"
                    aria-labelledby="revisiModalLabel{{ $ta->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title" id="revisiModalLabel{{ $ta->id }}">Revisi Proposal Tugas
                                    Akhir</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-4">
                                    <label for="file_revisi" class="form-label fw-semibold">Upload File Revisi</label>
                                    <input type="file" class="form-control" id="file_revisi" name="file_revisi"
                                        accept=".pdf,.doc,.docx" required>
                                </div>
                                <div>
                                    <label for="point_revisi" class="form-label fw-semibold">Point Revisi</label>
                                    <textarea class="form-control" id="point_revisi" name="point_revisi" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer gap-2">
                                <button type="button" class="btn btn-secondary btn-rounded-pill px-4"
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
                <div class="alert alert-info rounded-3 fs-5">Belum ada pengajuan tugas akhir.</div>
            </div>
        @endif
    </div>
</div>
@endsection
