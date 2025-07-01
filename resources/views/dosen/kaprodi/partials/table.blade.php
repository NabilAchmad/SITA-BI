<div class="table-responsive">
    <table class="table table-striped table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Judul Tugas Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tugasAkhir as $ta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ta->mahasiswa->user->name }}</td>
                    <td>{{ $ta->mahasiswa->nim }}</td>
                    <td>
                        {{ $ta->mahasiswa->prodi === 'd3' ? 'D3 Bahasa Inggris' : ($ta->mahasiswa->prodi === 'd4' ? 'D4 Bahasa Inggris' : '-') }}
                    </td>
                    <td>{{ $ta->judul }}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-id="{{ $ta->id }}"
                            onclick="showDetail(this)">
                            <i class="bi bi-info-circle"></i> Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
