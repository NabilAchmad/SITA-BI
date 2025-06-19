@forelse ($mahasiswaTidakLulus as $index => $mhs)
    <tr>
        <td class="text-center">{{ $mahasiswaTidakLulus->firstItem() + $index }}</td>
        <td>{{ $mhs->user->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
        <td class="text-center">
            @php
                $sidangTerakhir = $mhs->tugasAkhir?->sidangTerakhir ?? null;
            @endphp
            @if ($sidangTerakhir?->status === 'tidak_lulus')
                <span class="badge bg-danger">Tidak Lulus</span>
            @elseif ($sidangTerakhir?->status === 'lulus')
                <span class="badge bg-success">Sudah Lulus</span>
            @elseif ($sidangTerakhir?->status === 'lulus_revisi')
                <span class="badge bg-warning text-dark">Lulus Revisi</span>
            @else
                <span class="badge bg-light text-dark">-</span>
            @endif
        </td>

        <td class="text-center">
            @php
                $sidangMengulang = $mhs->tugasAkhir?->sidang?->where('status', 'tidak_lulus')->first();
            @endphp
            @if ($sidangMengulang)
                <button type="button" class="btn btn-sm btn-warning btn-jadwalkan"
                    data-sidang-id="{{ $sidangMengulang->id }}"
                    data-nama="{{ $mhs->user->name ?? '-' }}"
                    data-nim="{{ $mhs->nim ?? '-' }}"
                    data-judul="{{ $mhs->tugasAkhir->judul ?? '-' }}"
                    data-url="{{ route('jadwal-sidang.simpanPenguji', ['sidang_id' => $sidangMengulang->id]) }}">
                    <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Ulang
                </button>
            @else
                <span class="text-muted fst-italic">Tidak ada sidang</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Tidak ada mahasiswa yang mengulang sidang.
        </td>
    </tr>
@endforelse
