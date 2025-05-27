@push('styles')
    <style>
        .text-primary-donk {
            color: #004085 !important; /* Biru tua/navy */
        }

        /* Hover button take topic */
        .btn-take:hover {
            background-color: #267acc !important;
            box-shadow: 0 6px 16px #267acccc;
        }
    </style>
@endpush

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title text-primary-donk mb-0">List Topik Tugas Akhir</h4>
        <form method="GET" action="{{ route('topik-ta.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari topik..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Topik</th>
                        <th>Deskripsi</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topikList as $index => $topik)
                        <tr>
                            <td>{{ ($topikList->currentPage() - 1) * $topikList->perPage() + $index + 1 }}</td>
                            <td class="text-start">{{ $topik->judul_topik }}</td>
                            <td class="text-start">{{ $topik->deskripsi }}</td>
                            <td>{{ $topik->kuota }}</td>
                            <td>
                                <form method="POST" action="{{ route('topik-ta.take', $topik->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm btn-take">
                                        Ambil Topik
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Belum ada topik tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $topikList->withQueryString()->links() }}
        </div>
    </div>
</div>
