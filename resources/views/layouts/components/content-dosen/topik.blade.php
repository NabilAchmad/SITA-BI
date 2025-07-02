<!-- Tawaran Topik Card -->
<div class="card shadow rounded-4 border-0" id="topikCard">
    <div class="card-header bg-info text-white rounded-top-4 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-lightbulb me-2"></i>Kelola Topik dan Pengajuan
            </h5>
        </div>
    </div>

    <div class="card-body px-4 pb-4 pt-3">
        <div class="topik-list">
            <div class="topik-content position-relative" style="max-height: 250px; overflow-y: auto;">
                <!-- Loader -->
                <div class="loading-spinner text-center py-2 d-none text-muted">
                    <i class="fa fa-spinner fa-spin me-2"></i> Memuat ulang...
                </div>

                <!-- List -->
                <ul class="list-unstyled mb-0" id="topikList">
                    @forelse ($tawaranTopik as $topik)
                        <li class="mb-4 pb-3 border-bottom d-flex align-items-start">
                            <div class="flex-grow-1">
                                <div class="mb-1 d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($topik->created_at)->format('d M Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $topik->tugasAkhir->count() }} Mahasiswa mengambil
                                    </small>
                                </div>
                                <h6 class="fw-semibold text-info mb-1" style="font-size: 1.05rem;">
                                    {{ $topik->judul_topik }}
                                </h6>
                                <p class="mb-1" style="font-size: 0.95rem;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($topik->deskripsi), 120, '...') }}
                                </p>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    Kuota: {{ $topik->kuota }}
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-muted py-3">Belum ada tawaran topik yang dibuat.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
