<style>
  .judul-progress {
    color: #004085 !important; /* Warna biru tua/navy */
  }
</style>

<div class="container">
    <div class="text-center text-primary">
        <h1 class="fw-bold judul-progress">Progress Tugas Akhir</h1>
        <p class="text-muted">Pantau perkembangan pengajuan dan pelaksanaan tugas akhir secara detail.</p>
    </div>

    <div class="row justify-content-center">
        @forelse ($tugasAkhir as $ta)
            @php
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

            <div class="col-lg-10 col-xl-8 mb-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                        <h4 class="mb-0 fw-semibold">{{ $ta->judul }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-2"><strong>Status:</strong> {{ $statusLabel }}</p>
                        <p class="mb-2"><strong>Tanggal Pengajuan:</strong>
                            {{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('d M Y') }}</p>
                        <p class="mb-3"><strong>Abstrak:</strong><br>
                            <span class="text-muted">{{ $ta->abstrak }}</span>
                        </p>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Progress</label>
                            <div class="progress rounded-pill" style="height: 24px;">
                                <div class="progress-bar {{ $progressColor }} fw-bold" role="progressbar"
                                    style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ $progress }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3 rounded-bottom-4 border-top">
                        <a href="{{ asset('storage/' . $ta->file_path) }}" target="_blank"
                            class="btn btn-outline-primary rounded-pill px-4">
                            Lihat Proposal
                        </a>
                        <button type="button"
                            class="btn rounded-pill px-4 {{ $ta->status === 'diajukan' ? 'btn-warning' : 'btn-secondary' }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }} data-bs-toggle="collapse"
                            data-bs-target="#editForm{{ $ta->id }}">
                            Edit
                        </button>

                        <!-- Tombol Pembatalan Tugas Akhir -->
                        <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="collapse"
                            data-bs-target="#cancelForm{{ $ta->id }}"
                            {{ $ta->status !== 'diajukan' ? 'disabled' : '' }}>
                            Batalkan
                        </button>
                    </div>

                    <!-- Form Pembatalan Tugas Akhir -->
                    <div class="collapse" id="cancelForm{{ $ta->id }}">
                        <div class="card-body">
                            <form action="{{ route('tugasAkhir.cancelTA', $ta->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="alasan" class="form-label">Alasan Pembatalan</label>
                                    <textarea class="form-control" id="alasan" name="alasan" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill px-4">Kirim
                                    Pembatalan</button>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Form Edit (Hidden by Default) -->
                <div class="collapse" id="editForm{{ $ta->id }}">
                    <div class="card shadow-lg mt-3">
                        <div class="card-header bg-secondary text-white text-center py-4 rounded-top-4">
                            <h4 class="mb-0">Edit Proposal Tugas Akhir</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('tugasAkhir.update', $ta->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        value="{{ $ta->judul }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="abstrak" class="form-label">Abstrak</label>
                                    <textarea class="form-control" id="abstrak" name="abstrak" rows="3" required>{{ $ta->abstrak }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan
                                    Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info rounded-3">Belum ada pengajuan tugas akhir.</div>
            </div>
        @endforelse
    </div>
</div>
