<div class="card card-round shadow-sm">
    <div class="card-body pb-0" style="height: 472px; overflow: hidden; display: flex; flex-direction: column;">
        <!-- Judul dan Ikon -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-user-friends text-primary me-2 mt-1" style="font-size: 1.2rem;"></i>
                <div>
                    <div class="fw-semibold text-dark" style="font-size: 1rem;">Dosen Aktif</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Sedang online</div>
                </div>
            </div>
            <div class="text-primary fw-bold" style="font-size: 1.4rem;">{{ $dosenAktif->count() }}</div>
        </div>

        <!-- Daftar Dosen Aktif -->
        <div style="overflow-y: auto; flex-grow: 1; padding-right: 5px;">
            <ul class="list-group list-group-flush small">
                @forelse ($dosenAktif as $index => $dosen)
                    <li
                        class="list-group-item d-flex align-items-center justify-content-between px-1 py-2 border-bottom">
                        <span>{{ $index + 1 }}. {{ $dosen->nama }}</span>
                        <span class="badge bg-success rounded-circle" style="width: 10px; height: 10px;"
                            title="Online"></span>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center px-1 py-2">
                        Tidak ada dosen yang online
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
