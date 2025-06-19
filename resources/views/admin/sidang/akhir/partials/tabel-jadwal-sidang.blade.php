@forelse ($jadwalMahasiswa as $index => $jadwal)
    @php
        $ta = $jadwal->sidang->tugasAkhir ?? null;
        $mahasiswa = $ta?->mahasiswa ?? null;
    @endphp

    <tr>
        <td class="text-center">{{ $jadwalMahasiswa->firstItem() + $index }}</td>
        <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
        <td>{{ $ta?->judul ?? '-' }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}</td>
        <td class="text-center">
            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - 
            {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
        </td>
        <td>{{ $jadwal->ruangan?->lokasi ?? '-' }}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
                <a class="btn btn-warning btn-sm"
                    href="{{ route('jadwal-sidang.show', ['sidang_id' => $jadwal->sidang_id]) }}">
                    <i class="bi bi-eye"></i> Detail
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> Belum ada jadwal sidang yang tersedia.
        </td>
    </tr>
@endforelse
