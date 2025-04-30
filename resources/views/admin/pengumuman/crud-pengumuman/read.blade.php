<h1 class="mb-4">Daftar Pengumuman</h1>
<a href="{{ route('pengumuman.create') }}" class="btn btn-primary mb-3">Buat Pengumuman Baru</a>
<!-- Search & Sort Controls -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <input type="text" id="searchInput" class="form-control w-50" placeholder="Cari pengumuman...">
    <div>
        <select id="sortSelect" class="form-select">
            <option value="desc">Terbaru ke Terlama</option>
            <option value="asc">Terlama ke Terbaru</option>
        </select>
    </div>
</div>

<!-- Table -->
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center" id="pengumumanTable">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Tanggal Dibuat</th>
                <th>Audiens</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengumuman as $index => $item)
                <tr>
                    <td>{{ ($pengumuman->currentPage() - 1) * $pengumuman->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->isi }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_dibuat)->format('d M Y, H:i') }}</td>
                    <td>
                        @if ($item->audiens === 'registered_users')
                            Pengguna Terdaftar
                        @elseif ($item->audiens === 'dosen')
                            Dosen
                        @elseif ($item->audiens === 'mahasiswa')
                            Mahasiswa
                        @elseif ($item->audiens === 'guest')
                            Tamu
                        @elseif ($item->audiens === 'all_users')
                            Semua Pengguna
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                    data-id="{{ $item->id }}">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada pengumuman.</td>
                </tr>
            @endforelse
        </tbody>


    </table>
</div>

<!-- Pagination -->
<div>
    {{ $pengumuman->links('pagination::bootstrap-4') }}
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengumuman ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmHapus">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let idHapus = null;

        // Saat tombol "Hapus" ditekan, simpan ID dan tampilkan modal
        $('.btn-hapus').on('click', function() {
            idHapus = $(this).data('id');
            $('#modalHapus').modal('show');
        });

        // Saat tombol "Ya, Hapus" di modal ditekan
        $('#confirmHapus').on('click', function() {
            $.ajax({
                url: '/admin/pengumuman/' + idHapus, // pastikan ini sesuai prefix routenya
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    $('#modalHapus').modal('hide');
                    $('button[data-id="' + idHapus + '"]').closest('tr').remove();

                    // Perbaiki nomor urut
                    $('#pengumumanTable tbody tr').each(function(i) {
                        $(this).find('td:first').text(i + 1);
                    });
                },
                error: function() {
                    alert('Gagal menghapus pengumuman.');
                    $('#modalHapus').modal('hide');
                }
            });
        });

        // Fungsi Cari
        $('#searchInput').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $("#pengumumanTable tbody tr").filter(function() {
                $(this).toggle(
                    $(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -
                    1 ||
                    $(this).find('td:nth-child(3)').text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        // Fungsi Sort
        $('#sortSelect').on('change', function() {
            let order = $(this).val();
            let rows = $('#pengumumanTable tbody tr').get();

            rows.sort(function(a, b) {
                let dateA = new Date($(a).find('td:nth-child(4)').text());
                let dateB = new Date($(b).find('td:nth-child(4)').text());

                return order === 'asc' ? dateA - dateB : dateB - dateA;
            });

            // Setelah diurutkan, pasang lagi ke tbody
            $.each(rows, function(index, row) {
                $('#pengumumanTable tbody').append(row);
            });

            // Perbaiki nomor urut
            $('#pengumumanTable tbody tr').each(function(i) {
                $(this).find('td:first').text(i + 1);
            });
        });
    });
</script>

<!-- CSS Ringan -->
<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .table th {
        font-weight: bold;
    }

    .btn-hapus {
        min-width: 60px;
    }
</style>
