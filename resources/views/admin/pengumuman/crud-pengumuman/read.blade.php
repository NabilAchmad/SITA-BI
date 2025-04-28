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
            <tr>
                <td>1</td>
                <td>Libur Nasional</td>
                <td>Kantor akan libur pada tanggal 1 Mei 2025 dalam rangka Hari Buruh Internasional.</td>
                <td>18 Apr 2025</td>
                <td>Pengguna Terdaftar</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('pengumuman.edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-hapus" data-id="1">Hapus</button>
                    </div>
                </td>
            </tr>
            <!-- Tambahkan data lain disini -->
        </tbody>
    </table>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengumuman ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmHapus">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let idHapus = null;

        // Fungsi Hapus
        $('.btn-hapus').click(function() {
            idHapus = $(this).data('id');
            $('#modalHapus').modal('show');
        });

        $('#confirmHapus').click(function() {
            $('button[data-id="' + idHapus + '"]').closest('tr').remove();
            $('#modalHapus').modal('hide');
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
