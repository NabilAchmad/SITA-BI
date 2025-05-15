document.addEventListener('DOMContentLoaded', function () {
    let idHapus = null;
    let allRows = [];
    let originalRows = [];

    const table = document.querySelector('#pengumumanTable');
    const tbody = table.querySelector('tbody');
    const currentClientPage = parseInt(table.dataset.currentPage) || 1;
    const itemsPerPage = parseInt(table.dataset.perPage) || 10;
    const baseIndex = parseInt(table.dataset.baseIndex) || 0;

    const laravelPagination = document.querySelector('#laravelPagination');
    const customPagination = document.querySelector('#customPagination');

    Array.from(tbody.querySelectorAll('tr')).forEach(row => {
        if (row.dataset.id) {
            const rowData = {
                id: row.dataset.id,
                audiens: row.dataset.audiens,
                judul: row.querySelector('td:nth-child(2)').textContent,
                isi: row.querySelector('td:nth-child(3)').getAttribute('title') || row.querySelector('td:nth-child(3)').textContent,
                created_at: row.querySelector('td:nth-child(4)').textContent
            };
            originalRows.push(rowData);
        }
    });

    allRows = [...originalRows];

    document.querySelector('#confirmHapus')?.addEventListener('click', function () {
        if (!idHapus) return;

        fetch(`/admin/pengumuman/${idHapus}/soft-delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
            .then(res => {
                if (!res.ok) throw new Error();

                // Update data
                allRows = allRows.filter(row => row.id !== idHapus);
                originalRows = originalRows.filter(row => row.id !== idHapus);
                idHapus = null;
                $('#modalHapus').modal('hide');

                // Reset filter dan tampilkan semua data
                document.querySelector('#searchInput').value = '';
                document.querySelector('#audiensFilter').value = '';
                document.querySelector('#sortSelect').value = 'desc';
                allRows = [...originalRows];

                applyFilters(1);
            })
            .catch(() => alert('Gagal menghapus pengumuman.'));
    });

    document.querySelector('#resetFilter')?.addEventListener('click', () => {
        document.querySelector('#searchInput').value = '';
        document.querySelector('#audiensFilter').value = '';
        document.querySelector('#sortSelect').value = 'desc';
        allRows = [...originalRows];
        applyFilters(1);
    });

    ['#searchInput', '#audiensFilter', '#sortSelect'].forEach(selector => {
        document.querySelector(selector)?.addEventListener('input', () => applyFilters(1));
    });

    function applyFilters(page = 1) {
        const keyword = document.querySelector('#searchInput')?.value.toLowerCase().trim() || '';
        const audiens = document.querySelector('#audiensFilter')?.value || '';
        const sortOrder = document.querySelector('#sortSelect')?.value || 'desc';

        let filtered = allRows.filter(row => {
            const judul = row.judul.toLowerCase();
            const isi = row.isi.toLowerCase();
            return (judul.includes(keyword) || isi.includes(keyword)) &&
                (!audiens || row.audiens === audiens || row.audiens === 'all_users');
        });

        filtered.sort((a, b) => {
            const dA = new Date(a.created_at);
            const dB = new Date(b.created_at);
            return sortOrder === 'asc' ? dA - dB : dB - dA;
        });

        const totalItems = filtered.length;
        const totalPages = Math.max(Math.ceil(totalItems / itemsPerPage), 1);
        const activePage = Math.min(page, totalPages);
        const startIndex = (activePage - 1) * itemsPerPage;
        const paginated = filtered.slice(startIndex, startIndex + itemsPerPage);

        tbody.innerHTML = '';
        if (paginated.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada pengumuman yang cocok.</td></tr>';
        } else {
            paginated.forEach((rowData, i) => {
                const row = createRow(rowData, baseIndex + startIndex + i + 1);
                tbody.appendChild(row);
            });
        }

        const isFiltering = keyword || audiens;
        if (isFiltering) {
            laravelPagination.style.display = 'none';
            renderPagination(totalItems, activePage);
        } else {
            laravelPagination.style.display = 'block';
            customPagination.innerHTML = '';
        }

        reattachDeleteEvents();
    }

    function createRow(rowData, index) {
        const tr = document.createElement('tr');
        tr.dataset.id = rowData.id;
        tr.dataset.audiens = rowData.audiens;

        const isiText = stripTags(rowData.isi);
        const isiShort = isiText.length > 100 ? isiText.slice(0, 100) + '...' : isiText;

        const audiensLabels = {
            'registered_users': 'Pengguna Terdaftar',
            'dosen': 'Dosen',
            'mahasiswa': 'Mahasiswa',
            'guest': 'Tamu',
            'all_users': 'Semua Pengguna',
        };

        tr.innerHTML = `
            <td>${index}</td>
            <td>${rowData.judul}</td>
            <td class="truncate" title="${isiText}">${isiShort}</td>
            <td>${formatDate(rowData.created_at)}</td>
            <td>${audiensLabels[rowData.audiens] || '-'}</td>
            <td>
                <div class="d-flex justify-content-center gap-2">
                    <a href="/admin/pengumuman/${rowData.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus" data-id="${rowData.id}">Hapus</button>
                </div>
            </td>
        `;
        return tr;
    }

    function stripTags(html) {
        const div = document.createElement("div");
        div.innerHTML = html;
        return div.textContent || div.innerText || "";
    }

    function formatDate(dateString) {
        const d = new Date(dateString);
        return d.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    function renderPagination(totalItems, currentPage) {
        const totalPages = Math.max(Math.ceil(totalItems / itemsPerPage), 1);
        customPagination.innerHTML = '';
        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = `btn btn-sm mx-1 ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
            btn.textContent = i;
            btn.addEventListener('click', () => {
                applyFilters(i);
            });
            customPagination.appendChild(btn);
        }
    }

    function reattachDeleteEvents() {
        tbody.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function () {
                idHapus = this.dataset.id;
                $('#modalHapus').modal('show');
            });
        });
    }

    applyFilters(currentClientPage);
});
