<h1 class="mb-4">Kelola Akun Mahasiswa</h1>

<div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
    <!-- Input Pencarian -->
    <input type="text" id="searchInput" class="form-control w-50" placeholder="Cari Nama atau NIM..."
        onkeyup="filterTable()">

    <!-- Dropdown Sort Prodi -->
    <select id="sortProdi" class="form-select w-auto" onchange="sortTableByProdi()">
        <option value="">Urutkan Prodi</option>
        <option value="asc">Prodi A-Z</option>
        <option value="desc">Prodi Z-A</option>
    </select>
</div>

<table class="table table-bordered table-hover align-middle text-center shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswa as $index => $mhs)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mhs->user->name }}</td>
                <td>{{ $mhs->user->email }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->prodi }}</td>
                <td>
                    <a class="btn btn-warning btn-sm" href="{{ route('akun-mahasiswa.edit', $mhs->id) }}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data mahasiswa.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const name = row.cells[1]?.textContent.toLowerCase() || "";
            const nim = row.cells[3]?.textContent.toLowerCase() || "";

            if (name.includes(input) || nim.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function sortTableByProdi() {
        const table = document.querySelector("table tbody");
        const rows = Array.from(table.rows);
        const direction = document.getElementById("sortProdi").value;

        if (direction === "") return;

        rows.sort((a, b) => {
            const prodiA = a.cells[4].textContent.trim().toLowerCase();
            const prodiB = b.cells[4].textContent.trim().toLowerCase();

            if (prodiA < prodiB) return direction === "asc" ? -1 : 1;
            if (prodiA > prodiB) return direction === "asc" ? 1 : -1;
            return 0;
        });

        rows.forEach(row => table.appendChild(row));
    }
</script>
