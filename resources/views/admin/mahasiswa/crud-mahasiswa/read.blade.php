<div class="container mt-5">
    <h1 class="mb-4">Kelola Akun Mahasiswa</h1>
    <div class="card">
        <div class="card-header">
            <h5>Daftar Akun Mahasiswa</h5>
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Erland Mahasiswa</td>
                        <td>erland@example.com</td>
                        <td>Teknik Informatika</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ url('/kelola-akun/mahasiswa/edit') }}">Edit</a>
                            <a class="btn btn-danger btn-sm" href="{{ url('/kelola-akun/mahasiswa/edit') }}">Hapus</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Doe</td>
                        <td>janedoe@example.com</td>
                        <td>Sistem Informasi</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ url('/kelola-akun/mahasiswa/edit') }}">Edit</a>
                            <a class="btn btn-danger btn-sm" href="{{ url('/kelola-akun/mahasiswa/edit') }}">Hapus</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
