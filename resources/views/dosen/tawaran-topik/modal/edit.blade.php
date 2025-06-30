{{-- PERBAIKAN: Memastikan nama field (name) dan id elemen sesuai dengan controller dan Javascript --}}
<!-- Modal Edit Tawaran Topik -->
<div class="modal fade" id="editTawaranTopikModal" tabindex="-1" aria-labelledby="editTawaranTopikModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- ID form ini penting untuk di-target oleh Javascript --}}
        <form method="POST" id="formEditTawaranTopik" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTawaranTopikModalLabel">Edit Tawaran Topik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{-- Input 'name' ini tidak digunakan oleh validasi, tapi mungkin oleh Javascript --}}
                    <input type="hidden" id="edit_id_TawaranTopik" name="id">
                    <div class="mb-3">
                        <label for="edit_judul_topik" class="form-label">Judul Topik</label>
                        {{-- 'name' disesuaikan dengan aturan validasi: 'judul_topik' --}}
                        <input type="text" name="judul_topik" id="edit_judul_topik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        {{-- 'name' disesuaikan dengan aturan validasi: 'deskripsi' --}}
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kuota" class="form-label">Kuota</label>
                        {{-- 'name' disesuaikan dengan aturan validasi: 'kuota' --}}
                        <input type="number" name="kuota" id="edit_kuota" class="form-control" min="1"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
