$(document).ready(function () {
    let idHapus = null;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Event delegation untuk tombol hapus
    $('#pengumumanTable').on('click', '.btn-hapus', function (e) {
        e.preventDefault();
        idHapus = $(this).data('id');
        $('#modalHapus').modal('show');
    });

    // Konfirmasi hapus
    $('#confirmHapus').on('click', function () {
        if (!idHapus) return;

        $.ajax({
            url: '/admin/pengumuman/' + idHapus,
            type: 'POST',
            data: {
                _method: 'DELETE'
            },

            success: function (response) {
                $('#modalHapus').modal('hide');

                // Hapus baris dari tabel
                $('tr[data-id="' + idHapus + '"]').remove();

                // Reset ID
                idHapus = null;

                // Perbarui nomor urut
                $('#pengumumanTable tbody tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
            },
            error: function (xhr) {
                alert('Gagal menghapus pengumuman.');
                $('#modalHapus').modal('hide');
            }
        });
    });

    // Event untuk filter dan sort
    $('#searchInput, #audiensFilter, #sortSelect').on('input change', function () {
        applyFilters();
    });

    // Reset filter
    $('#resetFilter').on('click', function () {
        $('#searchInput').val('');
        $('#audiensFilter').val('');
        $('#sortSelect').val('desc');
        applyFilters();
    });

    // Fungsi filter dan sort
    function applyFilters() {
        let keyword = $('#searchInput').val().toLowerCase().trim();
        let audiens = $('#audiensFilter').val();
        let sortOrder = $('#sortSelect').val();

        // Ambil data asli dari tabel yang sedang ada
        let rows = $('#pengumumanTable tbody tr').filter(function () {
            return $(this).data('id') !== undefined; // hanya ambil yang punya data-id (data asli)
        }).clone();


        // Filter berdasarkan keyword dan audiens
        rows = rows.filter(function () {
            let judul = $(this).find('td:nth-child(2)').text().toLowerCase();
            let isi = $(this).find('td:nth-child(3)').text().toLowerCase();
            let rowAudiens = $(this).data('audiens');

            let matchSearch = judul.includes(keyword) || isi.includes(keyword);
            let matchAudiens = audiens === '' || rowAudiens === audiens;

            return matchSearch && matchAudiens;
        });

        // Sort berdasarkan tanggal
        rows.sort(function (a, b) {
            let dateA = new Date($(a).find('td:nth-child(4)').text());
            let dateB = new Date($(b).find('td:nth-child(4)').text());
            return sortOrder === 'asc' ? dateA - dateB : dateB - dateA;
        });

        // Tampilkan ulang hasil
        let tbody = $('#pengumumanTable tbody');
        tbody.empty();

        if (rows.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center">Tidak ada pengumuman yang cocok.</td>
                </tr>
            `);
        } else {
            rows.each(function (i) {
                $(this).find('td:first').text(i + 1); // nomor urut
                tbody.append(this);
            });
        }
    }

    // Jalankan saat pertama kali
    applyFilters();
});
