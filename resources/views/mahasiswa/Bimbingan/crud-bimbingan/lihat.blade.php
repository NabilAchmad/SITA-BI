<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold">Jadwal Bimbingan Saya</h3>

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse ($jadwals as $jadwal)
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100">
                    <div class="card-header bg-primary-gradient py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-white mb-0">
                            <i class="bi bi-person-badge me-2"></i>
                            {{ $jadwal->dosen->user->name ?? 'Dosen tidak diketahui' }}
                        </h5>
                        <span class="badge bg-white text-primary rounded-pill">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_bimbingan)->format('d M Y') }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <strong class="me-2">Status:</strong>
                            @if ($jadwal->status_bimbingan === 'diajukan')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    <i class="bi bi-hourglass-split me-1"></i> Diajukan
                                </span>
                            @elseif ($jadwal->status_bimbingan === 'disetujui')
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                </span>
                            @elseif ($jadwal->status_bimbingan === 'ditolak')
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                    <i class="bi bi-question-circle me-1"></i> -
                                </span>
                            @endif
                        </div>

                        @if ($jadwal->catatanBimbingan->count())
                            <div class="bimbingan-notes mt-4">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Catatan Bimbingan
                                </h6>

                                <div class="timeline-notes">
                                    @foreach ($jadwal->catatanBimbingan as $catatan)
                                        <div class="timeline-item mb-4 position-relative ps-4">
                                            <div class="timeline-badge 
                                                {{ $catatan->author_type === 'mahasiswa' ? 'bg-info' : 'bg-success' }} 
                                                position-absolute top-0 start-0 rounded-circle"
                                                style="width: 15px; height: 15px;">
                                            </div>

                                            <div class="card shadow-sm border-0">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span
                                                            class="fw-bold text-{{ $catatan->author_type === 'mahasiswa' ? 'info' : 'success' }}">
                                                            {{ ucfirst($catatan->author_type) }}
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <p class="mb-0">{{ $catatan->catatan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i> Terakhir diperbarui:
                            {{ \Carbon\Carbon::parse($jadwal->updated_at)->diffForHumans() }}
                        </small>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-three-dots"></i> Aksi
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada jadwal bimbingan</h5>
                        <p class="text-muted">Anda belum mengajukan jadwal bimbingan dengan dosen pembimbing</p>
                        <a href="#" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Jadwal
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
    <style>
        .bg-primary-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        }

        .timeline-notes::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush
