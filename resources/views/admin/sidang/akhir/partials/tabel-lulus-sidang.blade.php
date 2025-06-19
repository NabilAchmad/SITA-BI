@forelse ($mahasiswaLulus as $index => $mhs)
    @php
        $ta = $mhs->tugasAkhir ?? null;
        $sidangTerakhir = $ta?->sidangTerakhir ?? null;
        $jadwalSidang = $sidangTerakhir?->jadwalSidang ?? null;

        // Ambil rata-rata skor dari nilai sidang
        $nilaiAkhir = $sidangTerakhir
            ? \App\Models\NilaiSidang::where('sidang_id', $sidangTerakhir->id)->avg('skor')
            : null;
    @endphp

    <tr>
        <td class="text-center">
            {{ $mahasiswaLulus->firstItem() + $index }}
        </td>
        <td>{{ $mhs->user->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>{{ $ta?->judul ?? '-' }}</td>
        <td class="text-center">
            {{ $jadwalSidang?->tanggal ? \Carbon\Carbon::parse($jadwalSidang->tanggal)->format('d-m-Y') : '-' }}
        </td>
        <td class="text-center">
            {{ $nilaiAkhir ? number_format($nilaiAkhir, 2) : '-' }}
        </td>
        <td class="text-center">
            <a href="#" class="btn btn-sm btn-primary disabled">
                <i class="bi bi-printer"></i> Cetak
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> Belum ada sidang yang selesai.
        </td>
    </tr>
@endforelse
