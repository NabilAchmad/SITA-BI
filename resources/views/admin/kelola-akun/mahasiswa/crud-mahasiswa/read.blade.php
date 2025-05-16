<h1 class="mb-4">Kelola Akun Mahasiswa</h1>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-primary">Daftar Akun Mahasiswa</h5>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<table class="table table-bordered table-hover align-middle text-center shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Program Studi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswa as $index => $mhs)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mhs->user->name }}</td>
                <td>{{ $mhs->user->email }}</td>
                <td>{{ $mhs->prodi }}</td>
                <td>
                    <a class="btn btn-warning btn-sm" href="{{ route('akun-mahasiswa.edit', $mhs->id) }}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data mahasiswa.</td>
            </tr>
        @endforelse
    </tbody>
</table>
