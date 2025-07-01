{{-- Konten untuk tab kedua: Mengelola pengajuan dari mahasiswa --}}
<form method="GET" action="{{ route('dosen.tawaran-topik.index') }}" class="row g-3 mb-3 align-items-center">
    <input type="hidden" name="tab" value="mahasiswa">
    <div class="col-auto">
        <label for="prodi" class="col-form-label">Filter Prodi:</label>
    </div>
    <div class="col-md-4">
        <select name="prodi" id="prodi" class="form-select form-select-sm">
            <option value="">Semua Prodi</option>
            @foreach ($prodiList as $prodi)
                <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                    {{ Str::upper($prodi) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Prodi</th>
                <th>Topik yang Diajukan</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $index => $app)
                <tr>
                    <td>{{ ($applications->firstItem() ?? 0) + $index }}</td>
                    <td>{{ $app->mahasiswa->user->name }}</td>
                    <td>{{ $app->mahasiswa->nim }}</td>
                    <td>{{ Str::upper($app->mahasiswa->prodi) }}</td>
                    <td>{{ $app->tawaranTopik->judul_topik }}</td>
                    <td>{{ $app->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <form action="{{ route('dosen.tawaran-topik.approveApplication', $app) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('dosen.tawaran-topik.rejectApplication', $app) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada pengajuan dari mahasiswa saat ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-end mt-3">
    {{-- Menjaga parameter filter saat paginasi --}}
    {{ $applications->appends(request()->except('mahasiswa_page'))->links('pagination::bootstrap-5') }}
</div>
