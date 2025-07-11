@php
    // Logika untuk menentukan progress dan warna, diambil dari view utama Anda
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
        'selesai' => 'Selesai',
    ];

    $progress = match ($tugasAkhir->status) {
        'ditolak', 'dibatalkan' => 0,
        'diajukan' => 10,
        'disetujui' => 25,
        'revisi' => 60,
        'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 100,
        default => 5,
    };

    $progressColor = match ($tugasAkhir->status) {
        'diajukan' => 'warning',
        'disetujui' => 'info',
        'revisi' => 'primary',
        'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 'success',
        'ditolak', 'dibatalkan' => 'danger',
        default => 'secondary',
    };
@endphp

<div class="card border-0 shadow-lg mb-4 card-hover">
    <div class="card-header gradient-primary text-white border-0 rounded-top-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">{{ $tugasAkhir->judul }}</h2>
                <p class="mb-0 opacity-75">
                    <i class="bi bi-calendar-check text-white"></i>
                    Diajukan pada {{ \Carbon\Carbon::parse($tugasAkhir->tanggal_pengajuan)->format('d F Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-white text-primary fs-6 px-3 py-2 shadow-sm">
                    <i class="fas fa-flag me-1"></i>{{ $statusMap[$tugasAkhir->status] ?? 'Tidak Diketahui' }}
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-bold mb-0">Progress Tugas Akhir</h5>
                <span class="badge bg-primary fs-6">{{ $progress }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-{{ $progressColor }} progress-bar-striped progress-bar-animated"
                    role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>

        @if ($tugasAkhir->status === 'ditolak' && !empty($tugasAkhir->alasan_penolakan))
            <div class="alert alert-danger mt-3">
                <h6 class="fw-bold mb-1 text-danger">Alasan Penolakan</h6>
                <p class="mb-0 fst-italic">"{{ $tugasAkhir->alasan_penolakan }}"</p>
            </div>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-center border-top pt-4 mt-4">
            {{-- Tombol hanya muncul jika pembimbing sudah ada --}}
            @if ($tugasAkhir->peranDosenTa->isNotEmpty())

                {{-- Tombol Upload/Revisi Dokumen --}}
                <button type="button" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#uploadFileModal{{ $tugasAkhir->id }}">
                    <i class="fas fa-upload me-2"></i>Upload/Revisi Dokumen
                </button>

                {{-- Tombol Lihat Dokumen Terakhir (jika sudah ada) --}}
                @if ($tugasAkhir->file_path)
                    <a href="{{ asset('storage/' . $tugasAkhir->file_path) }}" target="_blank"
                        class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen Terakhir
                    </a>
                @endif

                {{-- Tombol Batalkan TA --}}
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="collapse"
                    data-bs-target="#cancelForm{{ $tugasAkhir->id }}">
                    <i class="fas fa-times-circle me-2"></i>Batalkan TA
                </button>
            @else
                <div class="alert alert-info text-center w-100 mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Aksi lebih lanjut dapat dilakukan setelah dosen pembimbing ditugaskan.
                </div>
            @endif
        </div>
    </div>
</div>
