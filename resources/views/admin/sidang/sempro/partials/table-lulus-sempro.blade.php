@forelse ($mahasiswaLulusSempro as $index => $mhs)
    @php
        $tugasAkhir = $mhs->tugasAkhir ?? null;
        $sidangTerakhir = $tugasAkhir?->sidangTerakhir ?? null;
    @endphp

    <tr>
        <td class="text-center">{{ $loop->iteration + ($mahasiswaLulusSempro->firstItem() - 1) }}</td>
        <td>{{ $mhs->user?->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>{{ $mhs->prodi ?? '-' }}</td>
        <td>{{ $tugasAkhir?->judul ?? '-' }}</td>
        <td class="text-center">
            @if ($sidangTerakhir)
                @if ($sidangTerakhir->status === 'lulus')
                    <span class="badge bg-success">Lulus</span>
                @elseif ($sidangTerakhir->status === 'lulus_revisi')
                    <span class="badge bg-warning text-dark">Lulus Revisi</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($sidangTerakhir->status) }}</span>
                @endif
            @else
                <span class="badge bg-light text-dark">-</span>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
                <a class="btn btn-primary btn-sm"
                    href="{{ route('pasca-sempro.detail', ['mahasiswa_id' => $mhs->id]) }}">Detail</a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> Belum ada mahasiswa yang lulus sidang sempro.
        </td>
    </tr>
@endforelse
