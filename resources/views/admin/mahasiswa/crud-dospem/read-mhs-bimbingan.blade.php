<h2 class="mb-4 fw-bold text-primary">Daftar Mahasiswa Sudah Memiliki Pembimbing</h2>

<table class="table table-bordered shadow-sm">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <th>Judul Tugas Akhir</th>
            <th>Pembimbing 1</th>
            <th>Pembimbing 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mahasiswa as $index => $mhs)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $mhs->user->name }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->prodi }}</td>
            <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
            <td>
                @php
                    $p1 = $mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing1')->first();
                @endphp
                {{ $p1 ? $p1->dosen->user->name : '-' }}
            </td>
            <td>
                @php
                    $p2 = $mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing2')->first();
                @endphp
                {{ $p2 ? $p2->dosen->user->name : '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
