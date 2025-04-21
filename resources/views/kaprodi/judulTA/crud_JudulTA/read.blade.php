<section id="acc-judul" class="values section">

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
                        <td><button class="btn btn-success" onclick="accJudul(1)">ACC</button></td>
                    </tr>
                    <tr>
                        <td>Rina Kusuma</td>
                        <td>Aplikasi Manajemen Perpustakaan Digital</td>
                        <td id="status-2">Menunggu</td>
                        <td><button class="btn btn-success" onclick="accJudul(2)">ACC</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function accJudul(id) {
            const statusCell = document.getElementById('status-' + id);
            statusCell.innerHTML = 'Disetujui';
            statusCell.classList.remove('text-warning');
            statusCell.classList.add('text-success');

            alert('Judul telah di-ACC!');
        }
    </script>

</section>
