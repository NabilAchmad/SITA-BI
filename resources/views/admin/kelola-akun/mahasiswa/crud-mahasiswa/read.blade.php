<h1 class="mb-4">Kelola Akun Mahasiswa</h1>

<div class="card-header">
    <h5>Daftar Akun Mahasiswa</h5>
</div>

<table class="table table-bordered rounded">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody class="table-hover">
        <tr>
            <td>1</td>
            <td>Erland Mahasiswa</td>
            <td>erland@example.com</td>
            <td>Teknik Informatika</td>
            <td>
                <a class="btn btn-warning btn-sm" href="{{ route('akun-mahasiswa.edit') }}">Edit</a>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Doe</td>
            <td>janedoe@example.com</td>
            <td>Sistem Informasi</td>
            <td>
                <a class="btn btn-warning btn-sm" href="{{ route('akun-mahasiswa.edit') }}">Edit</a>
            </td>
        </tr>
    </tbody>
</table>
