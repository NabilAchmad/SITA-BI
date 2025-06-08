<h1 class="mb-4 fw-bold text-primary">Mahasiswa Akan Sidang (Belum Dijadwalkan)</h1>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Judul Skripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mahasiswa as $index => $mhs)
            @php
                $ta = $mhs->tugasAkhir;
                $sidang = $ta?->sidang;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mhs->user->name ?? '-' }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $ta?->judul ?? '-' }}</td>
                <td>
                    @if ($sidang)
                        <a href="{{ route('jadwal-sidang.pilihPenguji', ['sidang_id' => $sidang->id]) }}"
                            class="btn btn-primary btn-sm">
                            Tentukan Jadwal
                        </a>
                    @else
                        <span class="text-muted">Belum daftar sidang</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
