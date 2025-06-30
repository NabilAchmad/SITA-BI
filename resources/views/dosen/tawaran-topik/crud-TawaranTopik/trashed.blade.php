@extends('layouts.template.main')
@section('title', 'Tawaran Topik Terhapus')
@section('content')

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div class="mb-2">
                <a href="{{ route('dosen.tawaran-topik.index') }}" class="btn btn-outline-primary btn-sm">
                   <i class="bi bi-file-earmark-post-fill"></i> Tawaran Topik
                </a>
            </div>
            <div class="text-center mt-2">
                <h4 class="card-title text-danger mb-0">Data Tawaran Topik Terhapus</h4>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                {{-- Tombol ini akan memicu SweetAlert --}}
                <button type="button" class="btn btn-danger" id="btnHapusSemua"
                    @if ($tawaranTopiks->isEmpty()) disabled @endif>
                    <i class="bi bi-trash me-1"></i> Hapus Semua Tawaran Topik
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered shadow-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Topik</th>
                            <th>Tanggal Dihapus</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tawaranTopiks as $index => $item)
                            <tr>
                                <td>{{ ($tawaranTopiks->firstItem() ?? 0) + $index }}</td>
                                <td>{{ $item->judul_topik }}</td>
                                <td>{{ $item->deleted_at ? $item->deleted_at->format('d M Y, H:i:s') : '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Form untuk Pulihkan --}}
                                        <form action="{{ route('dosen.tawaran-topik.restore', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-arrow-clockwise"></i> Pulihkan
                                            </button>
                                        </form>
                                        {{-- Tombol untuk Hapus Permanen (memicu SweetAlert) --}}
                                        <button type="button" class="btn btn-danger btn-sm btn-force-delete"
                                            data-url="{{ route('dosen.tawaran-topik.force-delete', $item->id) }}">
                                            <i class="bi bi-trash"></i> Hapus Permanen
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data terhapus.</td>
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

    {{-- Form tersembunyi untuk aksi delete. Javascript akan mengisi 'action' dan men-submit form ini. --}}
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi umum untuk konfirmasi SweetAlert
            function showDeleteConfirmation(deleteUrl, confirmationText) {
                swal({
                    title: "Apakah Anda yakin?",
                    text: confirmationText,
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Batal",
                            visible: true,
                            className: "btn btn-secondary"
                        },
                        confirm: {
                            text: "Ya, Hapus!",
                            className: "btn btn-danger"
                        },
                    },
                }).then((willDelete) => {
                    if (willDelete) {
                        // Jika dikonfirmasi, setel action form dan submit
                        $('#delete-form').attr('action', deleteUrl).submit();
                    } else {
                        swal("Dibatalkan", "Data Anda tetap aman.", {
                            icon: "info",
                            timer: 1500,
                            buttons: false,
                        });
                    }
                });
            }

            // Event untuk tombol 'Hapus Permanen' per baris
            $('.btn-force-delete').on('click', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                showDeleteConfirmation(url, "Data yang dihapus secara permanen tidak dapat dikembalikan!");
            });

            // Event untuk tombol 'Hapus Semua'
            $('#btnHapusSemua').on('click', function(e) {
                e.preventDefault();
                const url = "{{ route('dosen.tawaran-topik.force-delete-all') }}";
                showDeleteConfirmation(url, "Semua data di halaman ini akan dihapus permanen!");
            });

            // Script untuk menampilkan notifikasi dari session
            @if (session('alert'))
                @php $alert = session('alert'); @endphp
                swal({
                    title: "{{ $alert['title'] }}",
                    text: "{{ $alert['message'] }}",
                    icon: "{{ $alert['type'] }}",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary",
                        },
                    },
                });
            @endif
        });
    </script>
@endpush
