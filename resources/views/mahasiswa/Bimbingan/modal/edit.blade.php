@foreach ($jadwals as $jadwal)
    <!-- Modal Edit Jadwal Bimbingan -->
    <div class="modal fade" id="modalEditJadwal-{{ $jadwal->id }}" tabindex="-1"
        aria-labelledby="modalEditLabel-{{ $jadwal->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('bimbingan.updateJadwal', $jadwal->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel-{{ $jadwal->id }}">
                        <i class="bi bi-calendar-event me-2"></i> Ubah Jadwal Bimbingan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal_bimbingan-{{ $jadwal->id }}" class="form-label">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" id="tanggal_bimbingan-{{ $jadwal->id }}"
                            class="form-control" value="{{ $jadwal->tanggal_bimbingan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_bimbingan-{{ $jadwal->id }}" class="form-label">Jam Bimbingan</label>
                        <input type="time" name="jam_bimbingan" id="jam_bimbingan-{{ $jadwal->id }}"
                            class="form-control" value="{{ $jadwal->jam_bimbingan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="catatan-{{ $jadwal->id }}" class="form-label">Alasan Perubahan</label>
                        <textarea name="catatan" id="catatan-{{ $jadwal->id }}" rows="3" class="form-control"
                            placeholder="Contoh: Dosen berhalangan hadir, ingin ubah jadwal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
