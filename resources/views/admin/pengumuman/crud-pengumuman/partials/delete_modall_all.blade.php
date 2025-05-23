<!-- Modal Force Delete Semua Pengumuman -->
<div class="modal fade" id="modalForceDeleteAll" tabindex="-1" aria-labelledby="modalForceDeleteAllLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalForceDeleteAllLabel">Hapus Permanen Semua Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <strong>semua pengumuman yang sudah terhapus</strong> secara permanen?
                <br><small class="text-danger">Data yang dihapus tidak dapat dipulihkan.</small>
            </div>
            <div class="modal-footer">
                <form id="formDeleteAll" action="{{ route('pengumuman.force-delete-all') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="btnConfirmForceDeleteAll">Hapus Semua</button>
                </form>
            </div>
        </div>
    </div>
</div>
