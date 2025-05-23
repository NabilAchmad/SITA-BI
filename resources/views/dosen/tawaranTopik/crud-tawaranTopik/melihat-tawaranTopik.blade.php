<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\tawaranTopik\crud-tawaranTopik\melihat-tawaranTopik.blade.php -->

<div class="mb-4">
    <h5>Daftar Tawaran Topik yang Diajukan</h5>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered mt-2">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Mahasiswa</th>
                <th scope="col">Topik</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Tanggal Pengajuan</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ahmad Fauzi</td>
                <td>Pengembangan Sistem Informasi</td>
                <td>Membuat aplikasi sistem informasi akademik berbasis web.</td>
                <td>2025-05-21</td>
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
                <td>Menganalisis data penjualan menggunakan metode statistik.</td>
                <td>2025-05-20</td>
                <td><span class="badge bg-success">Disetujui</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Rizky Hidayat</td>
                <td>Mobile App Development</td>
                <td>Pengembangan aplikasi mobile untuk monitoring kesehatan.</td>
                <td>2025-05-19</td>
                <td><span class="badge bg-danger">Ditolak</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>