<h1 class="mb-4">Daftar Tawaran Topik</h1>
<a href="{{ url('/pengumuman/create') }}" class="btn btn-primary mb-3">Buat Tawaran Topik Baru</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Topik</th>
            <th>Deskripsi</th>
            <th>Dosen Pengampu</th>
            <th>Kuota</th>
            <th>Audiens</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Pengembangan Aplikasi Mobile</td>
            <td>Mengembangkan aplikasi mobile berbasis Flutter untuk sistem informasi akademik.</td>
            <td>Dr. Ahmad Basuki, S.Kom., M.T.</td>
            <td>2</td>
            <td>Pengguna Terdaftar</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ url('/pengumuman/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirm('Apakah Anda yakin ingin menghapus topik ini?')">Hapus</button>
                </div>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Sistem Rekomendasi Buku</td>
            <td>Perancangan sistem rekomendasi buku menggunakan machine learning untuk perpustakaan digital.</td>
            <td>Ir. Siti Hidayati, M.Kom.</td>
            <td>3</td>
            <td>Semua Pengguna</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ url('/pengumuman/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirm('Apakah Anda yakin ingin menghapus topik ini?')">Hapus</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
