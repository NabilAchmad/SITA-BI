<h1 class="mb-4 fw-bold text-primary">Jadwal Sidang</h1>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Judul Tugas Akhir</th>
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
                @endphp

                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
                    <td>{{ $ta?->judul ?? '-' }}</td>
                    <td class="text-center">{{ $jadwal->tanggal }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                    </td>
                    <td>{{ $jadwal->ruangan?->lokasi ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <a class="btn btn-warning btn-sm"
                                href="{{ route('jadwal-sidang.show', ['sidang_id' => $jadwal->sidang_id]) }}">Detail</a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">Belum ada jadwal sidang yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
