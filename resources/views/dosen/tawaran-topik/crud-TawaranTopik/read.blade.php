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
                <a href="{{ route('dosen.tawaran-topik.trashed') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-trash"></i> Tawaran Topik Terhapus
                </a>
            </div>
            <div class="text-center">
                <h4 class="card-title text-primary mb-0">Daftar Tawaran Topik</h4>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#tambahTawaranTopikModal">
                    <i class="bi bi-plus-lg me-1"></i> Ajukan Tawaran Topik Baru
                </button>
            </div>

            {{-- ... (kode form search tetap sama) ... --}}

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
                        @forelse ($tawaranTopiks as $index => $item)
                            <tr>
                                <td>{{ ($tawaranTopiks->firstItem() ?? 0) + $index }}</td>
                                <td>{{ $item->judul_topik }}</td>
                                <td class="truncate" title="{{ strip_tags($item->deskripsi) }}">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100, '...') }}
                                </td>
                                <td>{{ $item->kuota }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i:s') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit: Memicu modal edit --}}
                                        <button class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#editTawaranTopikModal"
                                            data-action="{{ route('dosen.tawaran-topik.update', $item) }}"
                                            data-judul="{{ $item->judul_topik }}" data-deskripsi="{{ $item->deskripsi }}"
                                            data-kuota="{{ $item->kuota }}">
                                            Edit
                                        </button>
                                        {{-- Tombol Hapus: Memicu modal delete --}}
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                            data-url="{{ route('dosen.tawaran-topik.destroy', $item) }}"
                                            data-bs-toggle="modal" data-bs-target="#hapusTawaranTopikModal">
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

            <div class="d-flex justify-content-end">
                {{ $tawaranTopiks->links() }}
            </div>
        </div>
    </div>

    {{-- Include semua modal yang dibutuhkan --}}
    @include('dosen.tawaran-topik.modal.create')
    @include('dosen.tawaran-topik.modal.edit')
    @include('dosen.tawaran-topik.modal.delete')

@endsection

@push('scripts')
    {{-- PERBAIKAN: Menghapus semua skrip AJAX dan SweetAlert. --}}
    {{-- Skrip minimal ini hanya untuk membuat modal dinamis. --}}
    <script>
        $(document).ready(function() {
            // Skrip untuk Modal Edit
            $(document).on('click', '.btn-edit', function() {
                var button = $(this);
                var action = button.data('action');
                var judul = button.data('judul');
                var deskripsi = button.data('deskripsi');
                var kuota = button.data('kuota');

                var modal = $('#editTawaranTopikModal');
                modal.find('form').attr('action', action);
                modal.find('#edit_judul_topik').val(judul);
                modal.find('#edit_deskripsi').val(deskripsi);
                modal.find('#edit_kuota').val(kuota);
            });

            // Skrip untuk Modal Delete
            $(document).on("click", ".btn-hapus", function() {
                let deleteUrl = $(this).data('url');
                // Mengatur atribut 'action' dari form di dalam modal delete
                $('#formHapusTawaranTopik').attr('action', deleteUrl);
            });
        });
    </script>
@endpush
