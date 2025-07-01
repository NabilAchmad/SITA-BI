{{-- Konten untuk tab pertama: Mengelola tawaran topik milik dosen --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTawaranTopikModal">
            <i class="bi bi-plus-lg me-1"></i> Ajukan Topik Baru
        </button>
    </div>
    <a href="{{ route('dosen.tawaran-topik.trashed') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-trash"></i> Topik Terhapus
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul Topik</th>
                <th>Deskripsi</th>
                <th>Kuota</th>
                <th>Tanggal Diajukan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tawaranTopiks as $index => $item)
                <tr>
                    <td>{{ ($tawaranTopiks->firstItem() ?? 0) + $index }}</td>
                    <td>{{ $item->judul_topik }}</td>
                    <td class="truncate" title="{{ strip_tags($item->deskripsi) }}">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}</td>
                    <td>{{ $item->kuota }}</td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                                data-bs-target="#editTawaranTopikModal"
                                data-action="{{ route('dosen.tawaran-topik.update', $item) }}"
                                data-judul="{{ $item->judul_topik }}" data-deskripsi="{{ $item->deskripsi }}"
                                data-kuota="{{ $item->kuota }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                data-url="{{ route('dosen.tawaran-topik.destroy', $item) }}" data-bs-toggle="modal"
                                data-bs-target="#hapusTawaranTopikModal">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Anda belum mengajukan tawaran topik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-end mt-3">
    {{ $tawaranTopiks->links() }}
</div>

