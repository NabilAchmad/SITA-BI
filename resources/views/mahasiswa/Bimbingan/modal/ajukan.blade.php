<div class="modal fade" id="modalAjukanJadwal" tabindex="-1" aria-labelledby="modalAjukanJadwalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('simpan.jadwal') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAjukanJadwalLabel">Ajukan Jadwal Bimbingan <span
                        id="modal_label_peran"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Hidden inputs -->
                <input type="hidden" name="dosen_id" id="modal_dosen_id" value="">
                <input type="hidden" name="tipe_dospem" id="modal_tipe_dospem" value="">

                <!-- Contoh field form jadwal (sesuaikan dengan kebutuhanmu) -->
                <div class="mb-3">
                    <label for="tanggal_jadwal" class="form-label">Tanggal Jadwal</label>
                    <input type="date" name="tanggal_jadwal" id="tanggal_jadwal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="waktu_jadwal" class="form-label">Waktu</label>
                    <input type="time" name="waktu_jadwal" id="waktu_jadwal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" rows="3" class="form-control" placeholder="Catatan tambahan jika ada"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Ajukan Jadwal</button>
            </div>
        </form>
    </div>
</div>
