<div class="card shadow-sm mb-4">
    <div class="card-header">
        {{-- Breadcrumb --}}
            @include('admin.mahasiswa.breadcrumbs.navlink')

        {{-- Judul --}}
        <div class="text-center">
            <h4 class="card-title text-primary mb-0">Daftar Mahasiswa Sudah Memiliki Pembimbing</h4>
        </div>
    </div>

    <div class="card-body">
        {{-- Tabs prodi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('list-mahasiswa') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('list-mahasiswa', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('list-mahasiswa', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        {{-- Search --}}
        <form method="GET" action="{{ route('list-mahasiswa') }}"
            class="row g-2 mb-3 justify-content-end">
            <input type="hidden" name="prodi" value="{{ request('prodi') }}">
            <div class="col-auto">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari nama atau NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered shadow-sm">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $index => $mhs)
                        <tr>
                            <td>{{ ($mahasiswa->firstItem() ?? 0) + $index }}</td>
                            <td>{{ $mhs->user->name }}</td>
                            <td>{{ $mhs->nim }}</td>
                            <td>{{ $mhs->prodi }}</td>
                            <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
                            <td>
                                @php
                                    $p1 = $mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing1')->first();
                                @endphp
                                {{ $p1->dosen->user->name ?? '-' }}
                            </td>
                            <td>
                                @php
                                    $p2 = $mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing2')->first();
                                @endphp
                                {{ $p2->dosen->user->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada mahasiswa dengan pembimbing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end">
            {{ $mahasiswa->links() }}
        </div>
    </div>
</div>
