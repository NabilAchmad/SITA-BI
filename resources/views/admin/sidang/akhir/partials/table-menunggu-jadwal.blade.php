@forelse ($mahasiswaMenunggu as $index => $mhs)
    <tr>
        <td class="text-center">{{ $mahasiswaMenunggu->firstItem() + $index }}</td>
        <td>{{ $mhs->user->name ?? '-' }}</td>
        <td>{{ $mhs->nim ?? '-' }}</td>
        <td>
            @switch(strtolower($mhs->prodi))
                @case('d4')
                    D4 Bahasa Inggris
                    @break
                @case('d3')
                    D3 Bahasa Inggris
                    @break
                @default
                    -
            @endswitch
        </td>
        <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
        <td class="text-center">
            @php
                $sidangTerakhir = $mhs->tugasAkhir->sidangTerakhir ?? null;
            @endphp
            @if ($sidangTerakhir)
                @switch($sidangTerakhir->status)
                    @case('tidak_lulus')
                        <span class="badge bg-danger">Pernah Tidak Lulus</span>
                        @break
                    @case('lulus')
                        <span class="badge bg-success">Sudah Lulus</span>
                        @break
                    @case('lulus_revisi')
                        <span class="badge bg-warning text-dark">Lulus Revisi</span>
                        @break
                    @case('menunggu')
                        <span class="badge bg-secondary">Menunggu</span>
                        @break
                    @case('dijadwalkan')
                        <span class="badge bg-info">Dijadwalkan</span>
                        @break
                    @default
                        <span class="badge bg-light text-dark">-</span>
                @endswitch
            @else
                <span class="badge bg-light text-dark">-</span>
            @endif
        </td>
        <td class="text-center">
            @php
                $sidang = $mhs->tugasAkhir->sidang?->firstWhere('status', 'menunggu');
            @endphp
            @if ($sidang)
                <button type="button"
                    class="btn btn-sm btn-success btn-jadwalkan"
                    data-sidang-id="{{ $sidang->id }}"
                    data-nama="{{ $mhs->user->name }}"
                    data-nim="{{ $mhs->nim }}"
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
            <i class="bi bi-exclamation-circle-fill me-2"></i> Tidak ada mahasiswa yang menunggu penjadwalan.
        </td>
    </tr>
@endforelse
