{{-- resources/views/dosen/kaprodi/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-striped table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Mahasiswa</th>
                <th style="width: 15%;">NIM</th>
                <th style="width: 15%;">Program Studi</th>
                <th>Judul Tugas Akhir</th>
                <th style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tugasAkhirCollection as $ta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    {{-- == PERBAIKAN DI SINI: Kode lebih aman dari data null == --}}
                    <td class="text-start">{{ $ta->mahasiswa?->user?->name ?? 'Mahasiswa tidak ditemukan' }}</td>
                    <td>{{ $ta->mahasiswa?->nim ?? '-' }}</td>
                    <td>
                        @if ($ta->mahasiswa)
                            <span class="badge {{ $ta->mahasiswa->prodi === 'D3' ? 'bg-info' : 'bg-primary' }}">
                                {{ $ta->mahasiswa->prodi === 'D3' ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris' }}
                            </span>
                        @else
                            <span class="badge bg-secondary">N/A</span>
                        @endif
                    </td>
                    <td class="text-start">{{ $ta->judul }}</td>
                    <td>
                        {{-- Menghapus `onclick` dan mengandalkan `data-id` untuk JS yang lebih bersih --}}
                        <button type="button" class="btn btn-primary btn-sm" data-id="{{ $ta->id }}">
                            <i class="bi bi-search"></i> Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Tidak ada data pengajuan pada kategori ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
