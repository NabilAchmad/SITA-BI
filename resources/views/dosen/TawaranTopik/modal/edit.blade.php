<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\TawaranTopik\modal\edit.blade.php -->
<!-- Modal Edit Tawaran Topik -->
<div class="modal fade" id="editTawaranTopikModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formEditTawaranTopik">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tawaran Topik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_TawaranTopik" name="id">
                    <div class="mb-3">
                        <label class="form-label">Judul Topik</label>
                        <input type="text" name="judul_topik" id="edit_judul_topik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" id="edit_kuota" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>