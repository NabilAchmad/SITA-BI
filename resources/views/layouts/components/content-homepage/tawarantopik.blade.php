<section id="tawarantopik" class="tawarantopik section">
    <div class="container" data-aos="fade-up">
        <!-- Judul -->
        <div class="section-title mt-3">
            <h1>Tawaran Topik</h1>
        </div>

        {{-- Card Tawaran Topik --}}
        @forelse ($topikTugasAkhir as $topik)
            <div class="card mb-4 bg-white shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">
                        {{ $topik->judul_topik }}
                    </h5>
                    <p class="card-text text-dark">
                        {{ $topik->deskripsi }}
                    </p>
                    <p class="card-text text-secondary d-flex justify-content-between align-items-center">
                        <span><strong>Dosen Pembimbing:</strong>
                            {{ optional($topik->user)->name ?? 'Tidak diketahui' }}</span>
                        <span class="badge bg-primary">Kuota Tersisa: {{ $topik->kuota }}</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center p-4">
                <p class="text-muted mb-0">Belum ada tawaran topik yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</section>
