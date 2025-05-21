<h1 class="mb-4 fw-bold text-primary">Jadwal Sidang</h1>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Judul Tugas Akhir</th>
                <th>Penguji 1</th>
                <th>Penguji 2</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Ruangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalList as $index => $jadwal)
                @php
                    $ta = $jadwal->sidang->tugasAkhir;
                    $mahasiswa = $ta?->mahasiswa;
                    $penguji1 = $ta?->peranDosenTa->firstWhere('peran', 'penguji1')?->dosen?->user?->name ?? '-';
                    $penguji2 = $ta?->peranDosenTa->firstWhere('peran', 'penguji2')?->dosen?->user?->name ?? '-';
                @endphp

                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
                    <td>{{ $ta?->judul ?? '-' }}</td>
                    <td>{{ $penguji1 }}</td>
                    <td>{{ $penguji2 }}</td>
                    <td class="text-center">{{ $jadwal->tanggal }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                    <td>{{ $jadwal->ruangan?->nama_ruangan ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a class="btn btn-warning btn-sm" href="{{ route('jadwal-sidang.edit', $jadwal->id) }}">
                                Edit
                            </a>
                            <form action="{{ route('jadwal-sidang.destroy', $jadwal->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Belum ada jadwal sidang yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
