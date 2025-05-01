<form action="{{ route('pengumuman.force-delete-all') }}" method="POST" id="formDeleteAll">
    @csrf
    @method('DELETE')

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modalForceDeleteAll" tabindex="-1" aria-labelledby="modalForceDeleteAllLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalForceDeleteAllLabel">Konfirmasi Hapus Semua Pengumuman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin <strong>menghapus permanen</strong> semua pengumuman ini? Tindakan ini tidak
                    bisa dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Semua Pengumuman</button>
                </div>
            </div>
        </div>
    </div>
</form>
