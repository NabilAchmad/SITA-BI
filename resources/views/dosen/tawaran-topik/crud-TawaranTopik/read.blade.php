{{-- filepath: resources/views/dosen/tawaran-topik/crud-TawaranTopik/read.blade.php --}}
@extends('layouts.template.main')
@section('title', 'Daftar Tawaran Topik')
@section('content')

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
            <a href="{{ route('tawaran-topik.trashed') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-trash"></i> Tawaran Topik Terhapus
            </a>
        </div>
        <div class="text-center">
            <h4 class="card-title text-primary mb-0">Daftar Tawaran Topik</h4>
        </div>
    </div>

    <div class="card-body">
        {{-- Tombol Buat --}}
        <div class="mb-3">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#tambahTawaranTopikModal">
                <i class="bi bi-plus-lg me-1"></i> Ajukan Tawaran Topik Baru
            </button>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('dosen.tawaran-topik.index') }}" class="row g-2 mb-3 justify-content-end">
            <input type="hidden" name="audiens" value="{{ request('audiens') }}">
            <div class="col-auto">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari judul/deskripsi..." value="{{ request('search') }}">
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
                        <th>Judul Topik</th>
                        <th>Deskripsi</th>
                        <th>Kuota</th>
                        <th>Tanggal Diajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tawaranTopik as $index => $item)
                        <tr>
                            <td>{{ ($tawaranTopik->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $item->judul_topik }}</td>
                            <td class="truncate" title="{{ strip_tags($item->deskripsi) }}">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100, '...') }}
                            </td>
                            <td>{{ $item->kuota }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i:s') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editTawaranTopikModal" data-id="{{ $item->id }}"
                                        data-judul="{{ $item->judul_topik }}" data-deskripsi="{{ $item->deskripsi }}"
                                        data-kuota="{{ $item->kuota }}">
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
                            <td colspan="6" class="text-center">Belum ada tawaran topik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end">
            {{ $tawaranTopik->links() }}
        </div>
    </div>
</div>

@include('dosen.tawaran-topik.modal.create')
@include('dosen.tawaran-topik.modal.edit')
@include('dosen.tawaran-topik.modal.delete')

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Tombol Hapus
        $(document).on("click", ".btn-hapus", function (e) {
            e.preventDefault();
            const id = $(this).data("id");

            swal({
                title: "Apakah Anda yakin?",
                text: "Tawaran topik yang dihapus tidak dapat dikembalikan!",
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
                        url: "/dosen/tawaran-topik/" + id + "/soft-delete",
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function (res) {
                            swal({
                                title: "Berhasil!",
                                text: "Tawaran topik telah dihapus.",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        text: "OK",
                                        className: "btn btn-primary",
                                    },
                                },
                            }).then(() => location.reload());
                        },
                        error: function () {
                            swal("Gagal!",
                                "Terjadi kesalahan saat menghapus tawaran topik.",
                                {
                                    icon: "error",
                                    buttons: false,
                                    timer: 2000,
                                });
                        },
                    });
                } else {
                    swal("Dibatalkan", "Tawaran topik Anda tetap aman.", {
                        icon: "info",
                        timer: 1500,
                        buttons: false,
                    });
                }
            });
        });

        // Modal Edit
        $('#editTawaranTopikModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var judul = button.data('judul');
            var deskripsi = button.data('deskripsi');
            var kuota = button.data('kuota');

            var modal = $(this);
            modal.find('form').attr('action', '/dosen/tawaran-topik/' + id + '/update');
            modal.find('#edit_id_TawaranTopik').val(id);
            modal.find('#edit_judul_topik').val(judul);
            modal.find('#edit_deskripsi').val(deskripsi);
            modal.find('#edit_kuota').val(kuota);
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
