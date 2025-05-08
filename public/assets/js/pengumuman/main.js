document.addEventListener('DOMContentLoaded', function () {
    let idHapus = null;
    let allRows = [];
    let originalRows = [];
    const table = document.querySelector('#pengumumanTable');
    let currentClientPage = parseInt(table.dataset.currentPage) || 1;
    const itemsPerPage = parseInt(table.dataset.perPage) || 10;

    const tbody = document.querySelector('#pengumumanTable tbody');
    const laravelPagination = document.querySelector('#laravelPagination');
    const customPagination = document.querySelector('#customPagination');

    // Inisialisasi data
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.forEach(row => {
        if (row.dataset.id) {
            originalRows.push(row.cloneNode(true));
        }
    });
    allRows = [...originalRows];

    // Delegasi event tombol hapus
    tbody.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('btn-hapus')) {
            e.preventDefault();
            idHapus = e.target.dataset.id;
            document.querySelector('#modalHapus').classList.add('show');
        }
    });

    // Konfirmasi hapus
    document.querySelector('#confirmHapus').addEventListener('click', function () {
        if (!idHapus) return;

        fetch('/admin/pengumuman/' + idHapus, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(response => {
            if (response.ok) {
                document.querySelector('#modalHapus').classList.remove('show');
                allRows = allRows.filter(row => row.dataset.id !== idHapus);
                originalRows = originalRows.filter(row => row.dataset.id !== idHapus);
                idHapus = null;
                applyFilters();
            } else {
                alert('Gagal menghapus pengumuman.');
                document.querySelector('#modalHapus').classList.remove('show');
            }
        });
    });

    // Reset filter
    document.querySelector('#resetFilter').addEventListener('click', function () {
        document.querySelector('#searchInput').value = '';
        document.querySelector('#audiensFilter').value = '';
        document.querySelector('#sortSelect').value = 'desc';

        allRows = originalRows.map(row => row.cloneNode(true));
        currentClientPage = 1;
        applyFilters();
    });

    // Filter input
    ['#searchInput', '#audiensFilter', '#sortSelect'].forEach(selector => {
        document.querySelector(selector).addEventListener('input', function () {
            currentClientPage = 1;
            applyFilters();
        });
    });

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        customPagination.innerHTML = '';

        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.classList.add('btn', 'btn-sm', 'mx-1');
            btn.classList.add(i === currentClientPage ? 'btn-primary' : 'btn-outline-primary');
            btn.textContent = i;
            btn.addEventListener('click', function () {
                currentClientPage = i;
                applyFilters();
            });
            customPagination.appendChild(btn);
        }
    }

    function applyFilters() {
        const keyword = document.querySelector('#searchInput').value.toLowerCase().trim();
        const audiens = document.querySelector('#audiensFilter').value;
        const sortOrder = document.querySelector('#sortSelect').value;

        tbody.innerHTML = '';

        // Filter
        let filtered = allRows.filter(row => {
            const judul = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const isi = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const rowAudiens = row.dataset.audiens;

            const matchSearch = judul.includes(keyword) || isi.includes(keyword);
            const matchAudiens = !audiens || rowAudiens === audiens || rowAudiens === 'all_users';
            return matchSearch && matchAudiens;
        });

        // Sort
        filtered.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(4)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(4)').textContent);
            return sortOrder === 'asc' ? dateA - dateB : dateB - dateA;
        });

        // Pagination
        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (currentClientPage > totalPages) {
            currentClientPage = totalPages || 1;
        }

        const startIndex = (currentClientPage - 1) * itemsPerPage;
        const paginatedRows = filtered.slice(startIndex, startIndex + itemsPerPage);

        if (paginatedRows.length === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.innerHTML = `<td colspan="6" class="text-center">Tidak ada pengumuman yang cocok.</td>`;
            tbody.appendChild(noDataRow);
        } else {
            paginatedRows.forEach((row, index) => {
                const newRow = row.cloneNode(true);
                const nomorCell = newRow.querySelector('td:nth-child(1)');
                if (nomorCell) {
                    nomorCell.textContent = startIndex + index + 1;
                }
                tbody.appendChild(newRow);
            });
        }

        const isFiltering = keyword || audiens;
        if (isFiltering) {
            laravelPagination.style.display = 'none';
            renderPagination(totalItems);
        } else {
            laravelPagination.style.display = 'block';
            customPagination.innerHTML = '';
        }
    }

    // Inisialisasi
    applyFilters();
});
