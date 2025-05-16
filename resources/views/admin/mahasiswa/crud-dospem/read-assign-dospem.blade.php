<h2 class="mb-4 fw-bold text-primary">Daftar Mahasiswa Belum Memiliki Pembimbing</h2>

<table class="table table-bordered shadow-sm">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @forelse($mahasiswa as $index => $mhs)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $mhs->user->name }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->prodi }}</td>
            <td>
                <a href="{{ route('penugasan-bimbingan.create', $mhs->id) }}" class="btn btn-primary btn-sm">
                    Pilih Pembimbing
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">Semua mahasiswa sudah memiliki pembimbing.</td>
        </tr>
        @endforelse
    </tbody>
</table>