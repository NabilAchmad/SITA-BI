<h1 class="mb-4">Data Pengumuman Terhapus</h1>

<!-- Tombol buka modal -->
<div class="mb-3">
    <button type="button" class="btn btn-danger" id="btnHapusSemua" @if ($pengumuman->isEmpty()) disabled @endif
        data-bs-toggle="modal" data-bs-target="#modalForceDeleteAll">
        Hapus Semua Pengumuman
    </button>
</div>


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

                    <button type="button" class="btn btn-danger btn-sm btn-force-delete" data-id="{{ $item->id }}"
                        data-bs-toggle="modal" data-bs-target="#modalHapus">
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
@include('admin.pengumuman.crud-pengumuman.partials.delete_modal_pengumuman')
<!-- Modal Konfirmasi Hapus Semua Pengumuman -->
@include('admin.pengumuman.crud-pengumuman.partials.delete_modall_all')
<!-- Script Konfirmasi & Penghapusan -->
<script src="{{ asset('assets/js/pengumuman/trashed.js') }}"></script>
