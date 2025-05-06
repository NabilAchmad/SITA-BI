<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosenPembimbing\jadwal\crud-jadwal\read.blade.php -->

<!-- Profil Mahasiswa -->
<div class="mb-4">
    <h5>Profil Mahasiswa</h5>
    <ul>
        <li><strong>Nama:</strong> Ahmad Fauzi</li>
        <li><strong>NIM:</strong> 123456789</li>
        <li><strong>Program Studi:</strong> Sistem Informasi</li>
    </ul>
</div>

<!-- Tabel Jadwal -->
<div class="table-responsive">
    <table class="table table-striped table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Mahasiswa</th>
                <th scope="col">Judul</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ahmad Fauzi</td>
                <td>Sistem Informasi Akademik</td>
                <td>2025-04-25</td>
                <td>10:00 - 11:00</td>
                <td><span class="badge bg-warning text-dark">Menunggu Persetujuan</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>Analisis Data Penjualan</td>
                <td>2025-04-26</td>
                <td>13:00 - 14:00</td>
                <td><span class="badge bg-success">Disetujui</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Rizky Hidayat</td>
                <td>Pengembangan Aplikasi Mobile</td>
                <td>2025-04-27</td>
                <td>09:00 - 10:00</td>
                <td><span class="badge bg-danger">Ditolak</span></td>
            </tr>
        </tbody>
    </table>
</div>