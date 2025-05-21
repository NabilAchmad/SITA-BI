<!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>ACC Judul Tugas Akhir</h1>
    </div>

    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="judulTable">
                    <tr>
                        <td>Erlan Nugroho</td>
                        <td>Sistem Informasi Akademik Terintegrasi</td>
                        <td id="status-1">Menunggu</td>
                        <td><button class="btn btn-success" onclick="accJudul(1)">ACC Judul</button></td>
                        <td><button class="btn btn-warning" onclick="tolakJudul(1)">Tolak Judul</button></td>
                    </tr>
                    <tr>
                        <td>Rina Kusuma</td>
                        <td>Aplikasi Manajemen Perpustakaan Digital</td>
                        <td id="status-2">Menunggu</td>
                        <td><button class="btn btn-success" onclick="accJudul(2)">ACC Judul</button></td>
                        <td><button class="btn btn-warning" onclick="tolakJudul(2)">Tolak Judul</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function accJudul(id) {
            fetch(`/kaprodi/judulTA/approve/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const statusCell = document.getElementById('status-' + id);
                statusCell.innerHTML = 'Disetujui';
                statusCell.classList.remove('text-warning', 'text-danger');
                statusCell.classList.add('text-success');
                alert(data.message);
            })
            .catch(error => {
                alert('Terjadi kesalahan saat meng-ACC judul.');
                console.error('Error:', error);
            });
        }

        function tolakJudul(id) {
            fetch(`/kaprodi/judulTA/reject/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const statusCell = document.getElementById('status-' + id);
                statusCell.innerHTML = 'Ditolak';
                statusCell.classList.remove('text-success');
                statusCell.classList.add('text-danger');
                alert(data.message);
            })
            .catch(error => {
                alert('Terjadi kesalahan saat menolak judul.');
                console.error('Error:', error);
            });
        }
    </script>
