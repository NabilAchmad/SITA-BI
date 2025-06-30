<!-- Modal Hapus Tawaran Topik -->
<div class="modal fade" id="hapusTawaranTopikModal" tabindex="-1" aria-labelledby="hapusTawaranTopikLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Form ini action-nya akan diisi secara dinamis oleh Javascript --}}
            <form id="formHapusTawaranTopik" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="hapusTawaranTopikLabel">Konfirmasi Hapus Tawaran Topik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus tawaran topik ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
