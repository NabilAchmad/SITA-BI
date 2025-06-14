@forelse ($jadwalMahasiswa as $index => $jadwal)
    @php
        $ta = $jadwal->sidang->tugasAkhir ?? null;
        $mahasiswa = $ta?->mahasiswa ?? null;
    @endphp

    <tr>
        <td class="text-center">{{ $loop->iteration + ($jadwalMahasiswa->firstItem() - 1) }}</td>
        <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
        <td>{{ $mahasiswa?->nim ?? '-' }}</td>
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
        <td colspan="8" class="text-center text-muted"><i class="bi bi-exclamation-circle-fill me-2"></i>Belum ada
            jadwal sidang yang tersedia.</td>
    </tr>
@endforelse
