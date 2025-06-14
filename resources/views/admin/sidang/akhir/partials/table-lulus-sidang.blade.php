@forelse ($mahasiswaLulus as $index => $mhs)
    @php
        $ta = $mhs->tugasAkhir ?? null;
        $sidangTerakhir = $ta?->sidangTerakhir ?? null;
    @endphp

    <tr>
        <td class="text-center">
            {{ ($mahasiswaLulus->currentPage() - 1) * $mahasiswaLulus->perPage() + $index + 1 }}
        </td>
        <td>{{ $mhs?->user?->name ?? '-' }}</td>
        <td>{{ $mhs?->nim ?? '-' }}</td>
        <td>{{ $ta?->judul ?? '-' }}</td>
        <td class="text-center">
            @if ($sidangTerakhir?->tanggal)
                {{ \Carbon\Carbon::parse($sidangTerakhir->tanggal)->format('d M Y') }}
            @else
                -
            @endif
        </td>
        <td class="text-center">
            <a href="#" class="btn btn-primary btn-sm">
                Cetak
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">
            <i class="bi bi-exclamation-circle-fill me-2"></i>Belum ada sidang yang selesai.
        </td>
    </tr>
@endforelse
