<div class="modal fade" id="modalUbahJadwal" tabindex="-1" aria-labelledby="modalUbahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUbahJadwalLabel">Ajukan Perubahan Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{-- route('mahasiswa.jadwal.request-change', $tugasAkhir->id) --}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan mengajukan perubahan untuk jadwal bimbingan yang sudah ada. Silakan isi jadwal baru
                        yang Anda usulkan dan berikan alasannya.</p>
                    <div class="mb-3">
                        <label for="tanggal_baru" class="form-label">Tanggal Baru yang Diusulkan</label>
                        <input type="date" name="tanggal_baru" id="tanggal_baru" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_baru" class="form-label">Jam Baru yang Diusulkan</label>
                        <input type="time" name="jam_baru" id="jam_baru" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="alasan_perubahan" class="form-label">Alasan Perubahan</label>
                        <textarea name="alasan_perubahan" id="alasan_perubahan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
