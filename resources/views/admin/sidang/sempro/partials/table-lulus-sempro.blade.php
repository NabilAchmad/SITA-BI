@forelse ($mahasiswaLulusSempro as $index => $mhs)
    @php
        $tugasAkhir = $mhs->tugasAkhir ?? null;
        $sidangTerakhir = $tugasAkhir?->sidangTerakhir ?? null;
    @endphp

    <tr>
        <td class="text-center">{{ $loop->iteration + ($mahasiswaLulusSempro->firstItem() - 1) }}</td>
        <td>{{ $mhs->user?->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>{{ $tugasAkhir?->judul ?? '-' }}</td>
        <td class="text-center">
            {{ $sidangTerakhir?->jadwalSidang?->tanggal
                ? \Carbon\Carbon::parse($sidangTerakhir->jadwalSidang->tanggal)->translatedFormat('d F Y')
                : '-' }}
        </td>

        <td class="text-center">
            <a class="btn btn-primary btn-sm" href="{{ route('sidang.kelola.sempro', ['mahasiswa_id' => $mhs->id]) }}">
                Detail
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> Belum ada mahasiswa yang lulus sidang sempro.
        </td>
    </tr>
@endforelse
