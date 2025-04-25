<h1 class="mb-4">Jadwal Sidang</h1>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Penguji 1</th>
                <th>Penguji 2</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Ruangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Implementasi Sistem Informasi Akademik</td>
                <td>Dr. Smith</td>
                <td>Prof. Johnson</td>
                <td>2023-12-01</td>
                <td>10:00 - 12:00</td>
                <td>Ruang 101</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-warning btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Edit</a>
                        <a class="btn btn-danger btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Hapus</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane Doe</td>
                <td>Analisis Data Menggunakan Machine Learning</td>
                <td>Dr. Brown</td>
                <td>Prof. Taylor</td>
                <td>2023-12-02</td>
                <td>13:00 - 15:00</td>
                <td>Ruang 102</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-warning btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Edit</a>
                        <a class="btn btn-danger btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Hapus</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Michael Smith</td>
                <td>Pengembangan Aplikasi Mobile</td>
                <td>Dr. Wilson</td>
                <td>Prof. Davis</td>
                <td>2023-12-03</td>
                <td>09:00 - 11:00</td>
                <td>Ruang 103</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-warning btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Edit</a>
                        <a class="btn btn-danger btn-sm" href="{{ url('/sidang/edit-jadwal') }}">Hapus</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
