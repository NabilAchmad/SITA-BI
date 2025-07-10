<div class="collapse" id="cancelForm{{ $tugasAkhir->id }}">
    <div class="card border-0 shadow-sm mb-4 rounded-4 card-hover">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 me-3"
                    style="width: 45px; height: 45px;">
                    <i class="fas fa-exclamation-triangle text-danger fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-danger mb-1">Form Pengajuan Pembatalan</h5>
                    <small class="text-muted">Silakan isi alasan pembatalan tugas akhir Anda secara lengkap.</small>
                </div>
            </div>

            <form action="{{ route('mahasiswa.tugas-akhir.cancel', $tugasAkhir->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="alasan_pembatalan_{{ $tugasAkhir->id }}" class="form-label fw-semibold">Alasan
                        Pembatalan</label>
                    <textarea class="form-control rounded-3" id="alasan_pembatalan_{{ $tugasAkhir->id }}" name="alasan" rows="4"
                        placeholder="Jelaskan alasan mengapa Anda ingin membatalkan tugas akhir ini..." required></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2"
                        data-bs-toggle="collapse" data-bs-target="#cancelForm{{ $tugasAkhir->id }}">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan Pembatalan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
