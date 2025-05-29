<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\TawaranTopik\crud-TawaranTopik\trashed.blade.php -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        {{-- Breadcrumbs --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('TawaranTopik.read') }}">Daftar Tawaran Topik</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tawaran Topik Terhapus</li>
            </ol>
        </nav>

        <div class="text-center mt-2">
            <h4 class="card-title text-danger mb-0">Data Tawaran Topik Terhapus</h4>
        </div>
    </div>

    <div class="card-body">
        {{-- Tombol Hapus Semua --}}
        <div class="mb-3">
            <button type="button" class="btn btn-danger" id="btnHapusSemua"
                @if ($pengumuman->isEmpty()) disabled @endif data-bs-toggle="modal"
                data-bs-target="#modalForceDeleteAll">
                <i class="bi bi-trash me-1"></i> Hapus Semua Tawaran Topik
            </button>
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered shadow-sm text-center">
                <thead class="table-light">
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
                            <td>{{ ($pengumuman->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->deleted_at->format('d M Y, H:i:s') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('TawaranTopik.restore', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-arrow-clockwise"></i> Pulihkan
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm btn-force-delete"
                                        data-id="{{ $item->id }}" data-bs-toggle="modal"
                                        data-bs-target="#modalHapusPermanen">
                                        <i class="bi bi-trash"></i> Hapus Permanen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end">
            {{ $pengumuman->links() }}
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Permanen Single --}}
<div class="modal fade" id="modalHapusPermanen" tabindex="-1" aria-labelledby="modalHapusPermanenLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formHapusPermanen">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusPermanenLabel">Konfirmasi Hapus Permanen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus tawaran topik ini secara permanen? Data yang sudah dihapus tidak
                    dapat dikembalikan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Semua Permanen --}}
<div class="modal fade" id="modalForceDeleteAll" tabindex="-1" aria-labelledby="modalForceDeleteAllLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('TawaranTopik.force-delete-all') }}">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForceDeleteAllLabel">Konfirmasi Hapus Semua Permanen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus semua tawaran topik yang terhapus secara permanen? Data yang sudah
                    dihapus tidak dapat dikembalikan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Semua</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Event delegation agar tetap bekerja jika elemen dinamis
            $(document).on('click', '.btn-force-delete', function() {
                const id = $(this).data('id');
                const url = `/admin/TawaranTopik/${id}/force-delete`;
                $('#formHapusPermanen').attr('action', url);
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!},
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
@endpush