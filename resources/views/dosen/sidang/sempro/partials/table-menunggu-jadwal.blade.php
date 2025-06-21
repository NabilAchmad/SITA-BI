@forelse ($mahasiswaMenunggu as $index => $mhs)
    <tr>
        <td class="text-center">{{ $mahasiswaMenunggu->firstItem() + $index }}</td>
        <td>{{ $mhs->user->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>
            @if ($mhs->prodi === 'd4')
                D4 Bahasa Inggris
            @elseif ($mhs->prodi === 'd3')
                D3 Bahasa Inggris
            @endif
        </td>
        <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
        <td class="text-center">
            @php
                $sidangTerakhir = $mhs->tugasAkhir->sidangTerakhir ?? null;
            @endphp

            @if ($sidangTerakhir && $sidangTerakhir->status === 'tidak_lulus')
                <span class="badge bg-danger">Pernah Tidak Lulus</span>
            @elseif ($sidangTerakhir && $sidangTerakhir->status === 'lulus')
                <span class="badge bg-success">Sudah Lulus</span>
            @elseif ($sidangTerakhir && $sidangTerakhir->status === 'lulus_revisi')
                <span class="badge bg-warning text-dark">Lulus Revisi</span>
            @elseif ($sidangTerakhir && $sidangTerakhir->status === 'menunggu')
                <span class="badge bg-secondary">Menunggu</span>
            @elseif ($sidangTerakhir && $sidangTerakhir->status === 'dijadwalkan')
                <span class="badge bg-info">Dijadwalkan</span>
            @else
                <span class="badge bg-light text-dark">-</span>
            @endif
        </td>
        <td class="text-center">
            @php
                $sidang = $mhs->tugasAkhir->sidang?->firstWhere('status', 'menunggu');
            @endphp
            @if ($sidang)
                <button type="button" class="btn btn-sm btn-success btn-jadwalkan" data-sidang-id="{{ $sidang->id }}"
                    data-nama="{{ $mhs->user->name }}" data-nim="{{ $mhs->nim }}"
                    data-judul="{{ $mhs->tugasAkhir->judul }}"
                    data-url="{{ route('jadwal-sidang.simpanPenguji', ['sidang_id' => $sidang->id]) }}">
                    <i class="bi bi-calendar-plus me-1"></i> Jadwalkan
                </button>
            @else
                <span class="text-muted fst-italic">Tidak ada sidang</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> Tidak ada mahasiswa yang
            menunggu penjadwalan.
        </td>
    </tr>
@endforelse
