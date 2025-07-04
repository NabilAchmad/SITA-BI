<div class="card shadow-sm mb-4">
    <div class="card-header">
        @include('admin.mahasiswa.breadcrumbs.navlink')
        <div class="text-center mt-5">
            <h4 class="card-title text-primary mb-0">Penugasan Pembimbing</h4>
            <p class="text-muted mb-0">Daftar mahasiswa yang membutuhkan penugasan pembimbing.</p>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('penugasan-bimbingan.index') }}">
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="prodi" class="form-select">
                        <option value="">Semua Program Studi</option>
                        <option value="D4" {{ request('prodi') == 'D4' ? 'selected' : '' }}>D4 Bahasa Inggris
                        </option>
                        <option value="D3" {{ request('prodi') == 'D3' ? 'selected' : '' }}>D3 Bahasa Inggris
                        </option>
                    </select>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama atau NIM mahasiswa..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tabel Mahasiswa --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Status Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugasAkhirList as $index => $ta)
                        @php
                            $mahasiswa = $ta->mahasiswa;
                            $pembimbing1 = $ta->peranDosenTA->where('peran', 'pembimbing1')->first();
                        @endphp
                        <tr>
                            <td class="text-center">{{ ($tugasAkhirList->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $mahasiswa->user->name }}</td>
                            <td class="text-center">{{ $mahasiswa->nim }}</td>
                            <td class="text-center">{{ strtoupper($mahasiswa->prodi) }}</td>
                            <td>{{ $ta->judul ?? '-' }}</td>
                            <td class="text-center">
                                @if ($pembimbing1)
                                    <span class="badge bg-success">Pembimbing 1:
                                        {{ $pembimbing1->dosen->user->name }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Ada Pembimbing</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalTetapkanPembimbing-{{ $ta->id }}">
                                    <i class="bi bi-person-plus-fill"></i> Tetapkan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada mahasiswa yang membutuhkan penugasan pembimbing saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {{ $tugasAkhirList->links() }}
        </div>
    </div>
</div>

@foreach ($tugasAkhirList as $ta)
    {{-- ... baris tabel Anda ... --}}

    {{-- Pastikan Anda meneruskan '$ta' sebagai '$tugasAkhir' --}}
    @include('admin.mahasiswa.modal.create-pembimbing', [
        'tugasAkhir' => $ta, // <--- INI BAGIAN PENTING
        'dosenList' => $dosenList,
    ])
@endforeach
