<h1 class="mb-4">Kelola Akun Dosen</h1>

<div class="card-header">
    <h5>Daftar Akun Dosen</h5>
</div>
<div class="card-body">
    <div class="mb-3">
        <a href="{{ url('admin/kelola-akun/dosen/tambah') }}" class="btn btn-primary">Tambah Akun</a>
    </div>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Dr. John Doe</td>
                <td>johndoe@example.com</td>
                <td>Dosen</td>
                <td>
                    <button class="btn btn-warning btn-sm">Edit</button>
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
