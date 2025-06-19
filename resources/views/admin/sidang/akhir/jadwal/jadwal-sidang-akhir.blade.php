@extends('layouts.template.main')
@section('title', 'Jadwal Sidang Akhir')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Jadwal Sidang Akhir</h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah dijadwalkan sidang akhir.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal</li>
                </ol>
            </nav>
        </div>

        {{-- Tabs Program Studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('jadwal.sidang.akhir', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        <form action="{{ route('jadwal.sidang.akhir') }}" method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" id="jadwalTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                        type="button" role="tab" aria-controls="menunggu" aria-selected="true">
                        Menunggu Penjadwalan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="jadwal-sidang-tab" data-bs-toggle="tab" data-bs-target="#jadwal-sidang"
                        type="button" role="tab" aria-controls="jadwal-sidang" aria-selected="false">
                        Jadwal Sidang Akhir
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tidak-lulus-tab" data-bs-toggle="tab" data-bs-target="#tidak-lulus"
                        type="button" role="tab" aria-controls="tidak-lulus" aria-selected="false">
                        Mengulang Sidang (Tidak Lulus Sidang Akhir)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lulus-tab" data-bs-toggle="tab" data-bs-target="#lulus" type="button"
                        role="tab" aria-controls="lulus" aria-selected="false">
                        Lulus Sidang Akhir
                    </button>
                </li>
            </ul>
                <div class="tab-content" id="jadwalTabContent">
                {{-- Tab Menunggu Jadwal --}}
                <div class="tab-pane fade show active" id="menunggu" role="tabpanel" aria-labelledby="menunggu-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th>Judul TA</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="menunggu">
                                @include('admin.sidang.akhir.partials.table-menunggu-jadwal')
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Jadwal Sidang Akhir --}}
                <div class="tab-pane fade" id="jadwal-sidang" role="tabpanel" aria-labelledby="jadwal-sidang-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Judul Tugas Akhir</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="jadwal-sidang">
                                @include('admin.sidang.akhir.partials.table-jadwal-sidang')                                
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Tidak Lulus dan Mengulang Sidang --}}
                <div class="tab-pane fade" id="tidak-lulus" role="tabpanel" aria-labelledby="tidak-lulus-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul TA</th>
                                    <th>Status Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tidak-lulus">
                                @include('admin.sidang.akhir.partials.table-ulang-sidang')
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Lulus Sidang Akhir --}}
                <div class="tab-pane fade" id="lulus" role="tabpanel" aria-labelledby="lulus-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul TA</th>
                                    <th>Tanggal Sidang</th>
                                    <th>Nilai Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="lulus">
                                @include('admin.sidang.akhir.partials.table-lulus-sidang')
                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $jadwalList->links() }}
    </div>

@endsection
