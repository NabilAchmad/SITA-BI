<div class="container mt-4">
    <h1 class="mb-4">Daftar Tawaran Topik</h1>

    <!-- Search Bar -->
    <div class="mb-4">
        <form action="{{ route('admin.berita-acara.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari topik..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul Topik</th>
                    <th>Deskripsi</th>
                    <th>Dosen Penawar</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topics as $index => $topic)
                <tr>
                    <td>{{ $topics->firstItem() + $index }}</td>
                    <td>{{ $topic->judul_topik }}</td>
                    <td>{{ $topic->deskripsi }}</td>
                    <td>{{ $topic->dosen_penawar }}</td>
                    <td>{{ $topic->kuota }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.berita-acara.ambil', $topic->id) }}" class="btn btn-success btn-sm">Ambil Topik</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada topik yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $topics->links() }}
    </div>
</div>
