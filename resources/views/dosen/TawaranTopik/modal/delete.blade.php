<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\TawaranTopik\modal\delete.blade.php -->
<!-- Modal Hapus Tawaran Topik -->
<div class="modal fade" id="hapusTawaranTopikModal" tabindex="-1" aria-labelledby="hapusTawaranTopikLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formHapusTawaranTopik" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="hapusTawaranTopikLabel">Konfirmasi Hapus Tawaran Topik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus tawaran topik ini?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>