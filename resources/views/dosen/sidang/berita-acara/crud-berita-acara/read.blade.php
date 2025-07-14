<div class="container mt-4">
    <h1 class="mb-4">Daftar Berita Acara</h1>

    <!-- Search Bar -->
    <div class="mb-4">
        <form action="#" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berita acara...">
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
                    <th>Judul</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data statis -->
                <tr>
                    <td>1</td>
                    <td>Berita Acara Rapat</td>
                    <td>Rapat</td>
                    <td>2023-10-01</td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">Unduh Word</a>
                        <a href="#" class="btn btn-secondary btn-sm">Export PDF</a>
                        <a href="/berita-acara/edit" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Berita Acara Kegiatan</td>
                    <td>Kegiatan</td>
                    <td>2023-10-05</td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">Unduh Word</a>
                        <a href="#" class="btn btn-secondary btn-sm">Export PDF</a>
                        <a href="/berita-acara/edit" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <!-- Tambahkan data lainnya -->
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
