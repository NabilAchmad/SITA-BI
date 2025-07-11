<div class="modal fade" id="modalBuatJadwal" tabindex="-1" aria-labelledby="modalBuatJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="modalBuatJadwalLabel">
                    <i class="bi bi-calendar-plus me-2"></i>Buat Jadwal Bimbingan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('dosen.jadwal.store', $tugasAkhir->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Buat jadwal bimbingan berikutnya untuk mahasiswa <strong
                            class="text-dark">{{ $mahasiswa->user->name }}</strong>.</p>

                    <div class="mb-3">
                        <label for="tanggal_bimbingan" class="form-label fw-semibold">Tanggal Bimbingan</label>
                        <input type="date" class="form-control" id="tanggal_bimbingan" name="tanggal_bimbingan"
                            value="{{ old('tanggal_bimbingan', now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="jam_bimbingan" class="form-label fw-semibold">Jam Bimbingan</label>
                        <input type="time" class="form-control" id="jam_bimbingan" name="jam_bimbingan"
                            value="{{ old('jam_bimbingan') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-send me-1"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
