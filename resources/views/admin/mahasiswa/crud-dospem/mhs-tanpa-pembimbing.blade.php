<div class="card shadow-sm mb-4">
    <div class="card-header">
        @include('admin.mahasiswa.breadcrumbs.navlink')
        <div class="text-center">
            <h4 class="card-title text-primary mb-0">Daftar Mahasiswa Belum Punya Pembimbing</h4>
        </div>
    </div>

    <div class="card-body">
        {{-- Tabs filter program studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('penugasan-bimbingan.index') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('penugasan-bimbingan.index', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('penugasan-bimbingan.index', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        {{-- Search form --}}
        <form method="GET" action="{{ route('penugasan-bimbingan.index') }}" class="row g-2 mb-3 justify-content-end">
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

        {{-- Table data --}}
        <div id="tableMahasiswa" class="table-responsive">
            <table class="table table-bordered shadow-sm">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($mahasiswa as $index => $mhs)
                        <tr>
                            <td>{{ $mahasiswa->firstItem() + $index }}</td>
                            <td>{{ $mhs->user->name }}</td>
                            <td>{{ $mhs->nim }}</td>
                            <td>
                                @if ($mhs->prodi === 'd4')
                                    D4 Bahasa Inggris
                                @elseif ($mhs->prodi === 'd3')
                                    D3 Bahasa Inggris
                                @else
                                    {{ $mhs->prodi }}
                                @endif
                            </td>
                            @php
                                $judul = $mhs->tugasAkhir->judul ?? '-';
                                $maxLength = 50;
                                $terpotong = strlen($judul) > $maxLength;
                            @endphp

                            <td>
                                @if ($terpotong)
                                    <span>{{ Str::limit($judul, $maxLength) }}</span>
                                    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal"
                                        data-bs-target="#lihatJudulModal-{{ $mhs->id }}">
                                        Lihat Selengkapnya
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="lihatJudulModal-{{ $mhs->id }}" tabindex="-1"
                                        aria-labelledby="lihatJudulModalLabel-{{ $mhs->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="lihatJudulModalLabel-{{ $mhs->id }}">Judul Lengkap
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $judul }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{ $judul }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalPembimbing-{{ $mhs->id }}">
                                    Pilih Pembimbing
                                </button>

                                @include('admin.mahasiswa.modal.create-pembimbing')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Semua mahasiswa sudah memiliki pembimbing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end">
                {{ $mahasiswa->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
