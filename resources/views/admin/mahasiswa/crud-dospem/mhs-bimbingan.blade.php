@extends('layouts.template.main')

@section('title', 'Daftar Mahasiswa Sudah Memiliki Pembimbing')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            @include('admin.mahasiswa.breadcrumbs.navlink')
            <div class="text-center">
                <h4 class="card-title text-primary mb-0">Daftar Mahasiswa Sudah Memiliki Pembimbing</h4>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Prodi -->
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

            <!-- Search -->
            <form method="GET" action="{{ route('list-mahasiswa') }}" class="row g-2 mb-3 justify-content-end">
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

            <!-- Tabel Mahasiswa -->
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $index => $mhs)
                            <tr>
                                <td>{{ ($mahasiswa->firstItem() ?? 0) + $index }}</td>
                                <td>{{ $mhs->user->name }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ strtoupper($mhs->prodi) }} Bahasa Inggris</td>
                                <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
                                <td>{{ optional($mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing1')->first())->dosen->user->name ?? '-' }}
                                </td>
                                <td>{{ optional($mhs->tugasAkhir->peranDosenTa->where('peran', 'pembimbing2')->first())->dosen->user->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditPembimbing-{{ $mhs->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada mahasiswa dengan pembimbing.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>

    <!-- Semua Modal Ditempatkan di Luar Tabel -->
    @foreach ($mahasiswa as $mhs)
        @include('admin.mahasiswa.partials.modal-edit-pembimbing', [
            'mhs' => $mhs,
            'dosen' => $dosen,
        ])
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="searchDosenEdit-"]').forEach(input => {
                const id = input.id.split('-')[1];
                const tbody = document.getElementById(`tbodyDosenEdit-${id}`);
                input.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    tbody.querySelectorAll('tr').forEach(row => {
                        const nama = row.querySelector('.nama-dosen').textContent
                            .toLowerCase();
                        row.style.display = nama.includes(filter) ? '' : 'none';
                    });
                });
            });

            document.querySelectorAll('[id^="modalEditPembimbing-"]').forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    const form = this.querySelector('form');
                    form.addEventListener('submit', function(e) {
                        const p1 = form.querySelector('input[name="pembimbing1"]:checked');
                        const p2 = form.querySelector('input[name="pembimbing2"]:checked');

                        if (!p1 || !p2) {
                            e.preventDefault();
                            swal("Peringatan!", "Pilih Pembimbing 1 dan Pembimbing 2!",
                                "warning");
                        } else if (p1.value === p2.value) {
                            e.preventDefault();
                            swal("Peringatan!", "Pembimbing 1 dan 2 tidak boleh sama!",
                                "warning");
                        }
                    });
                });
            });

            @if (session('success'))
                swal("Berhasil!", "{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                swal("Gagal!", "{{ session('error') }}", "error");
            @endif
        });
    </script>
@endpush
