<div class="container mt-4">
    <h1 class="mb-4">Daftar Tawaran Topik</h1>

    <!-- Search Bar -->
    <div class="mb-4">
        <form action="#" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari topik...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul Topik</th>
                    <th>Deskripsi</th>
                    <th>Dosen Penawar</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data statis -->
                <tr>
                    <td>1</td>
                    <td>Pengembangan Aplikasi Mobile</td>
                    <td>Aplikasi mobile berbasis Flutter untuk sistem monitoring tugas akhir.</td>
                    <td>Dr. Ahmad Basuki</td>
                    <td>2</td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-success btn-sm">Ambil Topik</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Sistem Rekomendasi Buku</td>
                    <td>Penggunaan machine learning untuk rekomendasi buku pada perpustakaan digital.</td>
                    <td>Ir. Siti Hidayati, M.Kom.</td>
                    <td>3</td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-success btn-sm">Ambil Topik</a>
                        </div>
                    </td>
                </tr>
                <!-- Tambahkan data lainnya jika diperlukan -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>
