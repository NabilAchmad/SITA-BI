@props(['collection', 'type', 'headers'])

@forelse($collection as $item)
    @switch($type)
        {{-- ================================================================= --}}
        {{-- CASE 1: MAHASISWA MENUNGGU DIJADWALKAN (STATUS TUGAS AKHIR: DRAFT) --}}
        {{-- ================================================================= --}}
        @case('menunggu')
            <tr>
                <td class="text-center">{{ $loop->iteration + $collection->firstItem() - 1 }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td class="text-center">{{ $item->nim ?? '-' }}</td>
                <td class="text-center">
                    @if ($item->prodi)
                        @if (strtolower($item->prodi) == 'd4')
                            D4 Bahasa Inggris
                        @else
                            D{{ substr($item->prodi, -1) }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->tugasAkhir->judul ?? '-' }}</td>
                <td class="text-center"><span class="badge bg-secondary">Draft</span></td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary btn-sm btn-workflow-jadwal"
                        data-tugas-akhir-id="{{ $item->tugasAkhir->id }}" data-nama="{{ $item->user->name ?? '-' }}"
                        data-nim="{{ $item->nim ?? '-' }}" data-judul="{{ $item->tugasAkhir->judul ?? '-' }}"
                        data-url-penguji="{{ route('jadwal-sempro.simpanPenguji', ['tugas_akhir_id' => $item->tugasAkhir->id]) }}">
                        Jadwalkan
                    </button>
                </td>
            </tr>
        @break

        {{-- ================================================================= --}}
        {{-- CASE 2: SIDANG SUDAH DIJADWALKAN --}}
        {{-- ================================================================= --}}
        @case('dijadwalkan')
            @php
                // Penyederhanaan variabel agar mudah dibaca
                $mahasiswa = $item->sidang->tugasAkhir->mahasiswa ?? null;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration + $collection->firstItem() - 1 }}</td>
                <td>{{ $mahasiswa->user->name ?? '-' }}</td>
                <td class="text-center">{{ $mahasiswa->nim ?? '-' }}</td>
                <td class="text-center">
                    {{ $mahasiswa->prodi
                        ? (strtolower($mahasiswa->prodi) == 'd4'
                            ? 'D4 Bahasa Inggris'
                            : 'D' . substr($mahasiswa->prodi, -1))
                        : '-' }}
                </td>
                <td>{{ $item->sidang->tugasAkhir->judul ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</td>
                <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                <td class="text-center">
                    <a href="{{ route('jadwal-sempro.show', ['sidang_id' => $item->sidang_id]) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                </td>
            </tr>
        @break

        {{-- ================================================================= --}}
        {{-- CASE 3: MAHASISWA TIDAK LULUS SIDANG SEBELUMNYA --}}
        {{-- ================================================================= --}}
        @case('tidak-lulus')
            <tr>
                <td class="text-center">{{ $loop->iteration + $collection->firstItem() - 1 }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td class="text-center">{{ $item->nim ?? '-' }}</td>
                <td class="text-center">{{ $item->prodi ? 'D' . substr($item->prodi, -1) : '-' }}</td>
                <td>{{ $item->tugasAkhir->judul ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge bg-danger">Tidak Lulus</span>
                </td>
                <td class="text-center">
                    {{-- Tombol ini bisa memicu alur pendaftaran ulang --}}
                    <button class="btn btn-warning btn-sm">Daftarkan Ulang</button>
                </td>
            </tr>
        @break

        {{-- ================================================================= --}}
        {{-- CASE 4: MAHASISWA SUDAH LULUS SEMPRO --}}
        {{-- ================================================================= --}}
        @case('lulus-sempro')
            @php
                $sidangTerakhir = $item->tugasAkhir->sidangTerakhir ?? null;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration + $collection->firstItem() - 1 }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td class="text-center">{{ $item->nim ?? '-' }}</td>
                <td class="text-center">{{ $item->prodi ? 'D' . substr($item->prodi, -1) : '-' }}</td>
                <td>{{ $item->tugasAkhir->judul ?? '-' }}</td>
                <td class="text-center">
                    {{ $sidangTerakhir ? \Carbon\Carbon::parse($sidangTerakhir->jadwalSidang->tanggal)->translatedFormat('d M Y') : '-' }}
                </td>
                <td class="text-center">
                    @if ($sidangTerakhir)
                        <span class="badge bg-success">{{ Str::title(str_replace('_', ' ', $sidangTerakhir->status)) }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @break
    @endswitch
    @empty
        <tr>
            <td colspan="{{ count($headers) }}" class="text-center text-muted py-3">
                <i class="bi bi-exclamation-circle-fill me-1"></i> Tidak ada data untuk ditampilkan.
            </td>
        </tr>
    @endforelse
