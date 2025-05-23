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

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <div class="mb-2">
            <a href="{{ route('pengumuman.trashed') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-trash"></i> Pengumuman Terhapus
            </a>
        </div>

        <div class="text-center">
            <h4 class="card-title text-primary mb-0">Daftar Pengumuman</h4>
        </div>
    </div>

    <div class="card-body">
        {{-- Tabs audiens --}}
        <ul class="nav nav-tabs mb-3">
            @php
                $audiensList = [
                    null => 'All',
                    'all_users' => 'Semua Pengguna',
                    'mahasiswa' => 'Mahasiswa',
                    'dosen' => 'Dosen',
                    'registered_users' => 'Pengguna Terdaftar',
                    'guest' => 'Tamu',
                ];
            @endphp
            @foreach ($audiensList as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ request('audiens') === $key ? 'active' : '' }}"
                        href="{{ route('pengumuman.read', ['audiens' => $key]) }}">{{ $label }}</a>
                </li>
            @endforeach
        </ul>

        {{-- Tombol Buat --}}
        <div class="mb-3">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#tambahPengumumanModal">
                <i class="bi bi-plus-lg me-1"></i> Buat Pengumuman Baru
            </button>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('pengumuman.read') }}" class="row g-2 mb-3 justify-content-end">
            <input type="hidden" name="audiens" value="{{ request('audiens') }}">
            <div class="col-auto">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari judul atau isi..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered shadow-sm text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th>Tanggal Dibuat</th>
                        <th>Viewers</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengumuman as $index => $item)
                        <tr>
                            <td>{{ ($pengumuman->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $item->judul }}</td>
                            <td class="truncate" title="{{ strip_tags($item->isi) }}">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100, '...') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i:s') }}</td>
                            <td>
                                @php
                                    $aud = [
                                        'registered_users' => 'Pengguna Terdaftar',
                                        'dosen' => 'Dosen',
                                        'mahasiswa' => 'Mahasiswa',
                                        'guest' => 'Tamu',
                                        'all_users' => 'Semua Pengguna',
                                    ];
                                @endphp
                                {{ $aud[$item->audiens] ?? '-' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editPengumumanModal" data-id="{{ $item->id }}"
                                        data-judul="{{ $item->judul }}" data-isi="{{ $item->isi }}"
                                        data-audiens="{{ $item->audiens }}">
                                        Edit
                                    </button>

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

        {{-- Pagination --}}
        <div class="d-flex justify-content-end">
            {{ $pengumuman->links() }}
        </div>
    </div>
</div>

@include('admin.pengumuman.modal.create')
@include('admin.pengumuman.modal.edit')

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".btn-hapus", function(e) {
                e.preventDefault();
                const id = $(this).data("id");

                swal({
                    title: "Apakah Anda yakin?",
                    text: "Pengumuman yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Batal",
                            visible: true,
                            className: "btn btn-danger",
                        },
                        confirm: {
                            text: "Ya, hapus!",
                            className: "btn btn-success",
                        },
                    },
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "/admin/pengumuman/" + id +"/soft-delete",
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"),
                            },
                            success: function(res) {
                                swal({
                                    title: "Berhasil!",
                                    text: "Pengumuman telah dihapus.",
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            text: "OK",
                                            className: "btn btn-primary",
                                        },
                                    },
                                }).then(() => location.reload());
                            },
                            error: function() {
                                swal("Gagal!",
                                    "Terjadi kesalahan saat menghapus pengumuman.", {
                                        icon: "error",
                                        buttons: false,
                                        timer: 2000,
                                    });
                            },
                        });
                    } else {
                        swal("Dibatalkan", "Pengumuman Anda tetap aman.", {
                            icon: "info",
                            timer: 1500,
                            buttons: false,
                        });
                    }
                });
            });

            $('#editPengumumanModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var judul = button.data('judul');
                var isi = button.data('isi');
                var audiens = button.data('audiens');

                var modal = $(this);
                modal.find('form').attr('action', '/admin/pengumuman/' + id + '/update');
                modal.find('#edit_id_pengumuman').val(id);
                modal.find('#edit_judul').val(judul);
                modal.find('#edit_isi').val(isi);
                modal.find('#edit_audiens').val(audiens);
            });

            @if (session('success'))
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary",
                        },
                    },
                });
            @endif

            @if (session('error'))
                swal({
                    title: "Gagal!",
                    text: "{{ session('error') }}",
                    icon: "error",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-danger",
                        },
                    },
                });
            @endif
        });
    </script>
@endpush
