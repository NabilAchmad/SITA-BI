<h1 class="mb-4">Daftar Pengumuman</h1>
<a href="{{url('/pengumuman/create')}}" class="btn btn-primary mb-3">Buat Pengumuman Baru</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Isi</th>
            <th>Tanggal Dibuat</th>
            <th>Audiens</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Libur Nasional</td>
            <td>Kantor akan libur pada tanggal 1 Mei 2025 dalam rangka Hari Buruh Internasional.</td>
            <td>18 Apr 2025</td>
            <td>Pengguna Terdaftar</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{url('/pengumuman/edit')}}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">Hapus</button>
                </div>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Rapat Koordinasi</td>
            <td>Rapat koordinasi bulanan akan diadakan pada hari Senin, pukul 09.00 WIB di ruang rapat utama.</td>
            <td>15 Apr 2025</td>
            <td>Tamu</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{url('/pengumuman/edit')}}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">Hapus</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
