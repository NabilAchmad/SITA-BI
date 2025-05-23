<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\sidang\crud-sidang\melihatSidang.blade.php -->

<div class="mb-4">
    <h5>Daftar Jadwal Sidang</h5>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered mt-2">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Mahasiswa</th>
                <th scope="col">Judul Sidang</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
                <th scope="col">Gedung</th>
                <th scope="col">Ruangan</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ahmad Fauzi</td>
                <td>Sistem Informasi Akademik</td>
                <td>2025-06-10</td>
                <td>09:00 - 10:00</td>
                <td>Gedung A</td>
                <td>101</td>
                <td><span class="badge bg-warning text-dark">Menunggu Persetujuan</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>Analisis Data Penjualan</td>
                <td>2025-06-11</td>
                <td>10:00 - 11:00</td>
                <td>Gedung B</td>
                <td>202</td>
                <td><span class="badge bg-success">Disetujui</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Rizky Hidayat</td>
                <td>Pengembangan Aplikasi Mobile</td>
                <td>2025-06-12</td>
                <td>13:00 - 14:00</td>
                <td>Gedung C</td>
                <td>303</td>
                <td><span class="badge bg-danger">Ditolak</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>