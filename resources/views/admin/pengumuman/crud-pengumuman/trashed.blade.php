<h1 class="mb-4">Data Pengumuman Terhapus</h1>

<form action="{{ route('pengumuman.force-delete-all') }}" method="POST" id="formDeleteAll" class="mb-3">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger" id="btnHapusSemua">Hapus Semua Pengumuman</button>
</form>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Tanggal Dihapus</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pengumuman as $index => $item)
            <tr>
                <td>{{ ($pengumuman->currentPage() - 1) * $pengumuman->perPage() + $loop->iteration }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->deleted_at->format('d M Y, H:i') }}</td>
                <td>
                    <form action="{{ route('pengumuman.restore', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Pulihkan</button>
                    </form>

                    <button type="button" class="btn btn-danger btn-sm btn-force-delete" data-id="{{ $item->id }}">
                        Hapus Permanen
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data terhapus.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $pengumuman->links('pagination::bootstrap-4') }}

<!-- Modal Konfirmasi Hapus Satu Pengumuman -->
<div class="modal fade" id="modalForceDeleteSingle" tabindex="-1" aria-labelledby="modalForceDeleteSingleLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalForceDeleteSingleLabel">Konfirmasi Hapus Permanen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin <strong>menghapus permanen</strong> pengumuman ini? Tindakan ini tidak bisa
                dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnConfirmForceDeleteSingle">Hapus Permanen</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Semua Pengumuman -->
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
                bisa
                dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnConfirmForceDeleteAll">Hapus Semua
                    Pengumuman</button>
            </div>
        </div>
    </div>
</div>


<!-- Script Konfirmasi & Penghapusan -->
<script>
    let forceDeleteId = null;

    document.getElementById('btnHapusSemua').addEventListener('click', function() {
        // Menampilkan modal konfirmasi untuk menghapus semua pengumuman
        let modalAll = new bootstrap.Modal(document.getElementById('modalForceDeleteAll'));
        modalAll.show();

        // Menyimpan aksi penghapusan semua pengumuman
        document.getElementById('btnConfirmForceDeleteAll').onclick = function() {
            document.getElementById('formDeleteAll').submit();
        };
    });

    document.querySelectorAll('.btn-force-delete').forEach(button => {
        button.addEventListener('click', function() {
            forceDeleteId = this.dataset.id;

            // Menampilkan modal untuk penghapusan satu pengumuman
            let modalSingle = new bootstrap.Modal(document.getElementById('modalForceDeleteSingle'));
            modalSingle.show();
        });
    });

    document.getElementById('btnConfirmForceDeleteSingle').addEventListener('click', function() {
        if (!forceDeleteId) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pengumuman/force-delete/${forceDeleteId}`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    });
</script>
