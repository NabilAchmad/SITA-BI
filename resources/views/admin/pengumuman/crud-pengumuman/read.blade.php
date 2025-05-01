<h1 class="mb-4">Daftar Pengumuman</h1>
<a href="{{ route('pengumuman.create') }}" class="btn btn-primary mb-3">Buat Pengumuman Baru</a>

@include('admin.pengumuman.crud-pengumuman.partials.search_sort')

<!-- Table -->
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center" id="pengumumanTable">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Tanggal Dibuat</th>
                <th>Audiens</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengumuman as $index => $item)
                <tr data-audiens="{{ $item->audiens }}" data-id="{{ $item->id }}">
                    <td>{{ ($pengumuman->currentPage() - 1) * $pengumuman->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->judul }}</td>
                    <td class="truncate" title="{{ strip_tags($item->isi) }}">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100, '...') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i:s') }}</td>
                    <td>
                        @if ($item->audiens === 'registered_users')
                            Pengguna Terdaftar
                        @elseif ($item->audiens === 'dosen')
                            Dosen
                        @elseif ($item->audiens === 'mahasiswa')
                            Mahasiswa
                        @elseif ($item->audiens === 'guest')
                            Tamu
                        @elseif ($item->audiens === 'all_users')
                            Semua Pengguna
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <!-- Tombol yang akan trigger modal -->
                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $item->id }}">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada pengumuman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div>
    {{ $pengumuman->links('pagination::bootstrap-4') }}
</div>

<!-- Modal Hapus -->
@include('admin.pengumuman.crud-pengumuman.partials.delete_modal')

<!-- JavaScript -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/pengumuman/main.js') }}"></script>
<!-- CSS Ringan -->
<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .table th {
        font-weight: bold;
    }

    .btn-hapus {
        min-width: 60px;
    }

    .truncate {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
