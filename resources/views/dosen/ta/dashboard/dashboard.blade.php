<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\ta\dashboard\dashboard.blade.php -->
@extends('layouts.template.main')

@section('title', 'Dashboard Sidang Mahasiswa')

@section('content')
<div class="container-fluid">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1 text-primary"><i class="bi bi-mortarboard-fill me-2"></i> Dashboard Tugas Akhir Mahasiswa</h1>
            <p class="text-muted mb-0">Lihat daftar mahasiswa yang sidang dan detail laporan kemajuan tugas akhirnya.</p>
        </div>
    </div>

    {{-- Tabs Program Studi --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                href="?{{ http_build_query(['search' => request('search')]) }}">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                href="?prodi=D4&{{ http_build_query(['search' => request('search')]) }}">D4</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                href="?prodi=D3&{{ http_build_query(['search' => request('search')]) }}">D3</a>
        </li>
    </ul>

    {{-- Form Cari Nama Mahasiswa --}}
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="hidden" name="prodi" value="{{ request('prodi') }}">
            <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>

    {{-- List Mahasiswa Sidang --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-primary">Daftar Tugas Akhir Mahasiswa</h5>
        </div>
        <div class="card-body">
            @php
                $search = strtolower(request('search', ''));
                $prodi = request('prodi');
                $filtered = $mahasiswaSidang->filter(function($mhs) use ($search, $prodi) {
                    $matchProdi = !$prodi || $mhs->prodi === $prodi;
                    $matchSearch = !$search || strpos(strtolower($mhs->nama), $search) !== false;
                    return $matchProdi && $matchSearch;
                });
            @endphp
            @if($filtered->isEmpty())
                <div class="alert alert-warning">Belum ada mahasiswa yang sidang.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Judul TA</th>
                                <th>Program Studi</th>
                                <th>Dosen Pembimbing</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filtered as $mhs)
                                <tr>
                                    <td>{{ $mhs->nama }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->judul_ta }}</td>
                                    <td>{{ $mhs->prodi }}</td>
                                    <td>{{ $mhs->dosen_pembimbing }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#detailKemajuan{{ $mhs->id }}"
                                            aria-expanded="false"
                                            aria-controls="detailKemajuan{{ $mhs->id }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="detailKemajuan{{ $mhs->id }}">
                                    <td colspan="6">
                                        {{-- Laporan Kemajuan TA --}}
                                        <div class="card card-body">
                                            <h6 class="text-primary mb-3">Laporan Kemajuan Tugas Akhir</h6>
                                            @if(isset($mhs->kemajuan) && count($mhs->kemajuan) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Catatan</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($mhs->kemajuan as $item)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                                                    <td>{{ $item->catatan }}</td>
                                                                    <td>
                                                                        @if ($item->status_revisi == 'ACC')
                                                                            <span class="badge bg-success">ACC</span>
                                                                        @elseif ($item->status_revisi == 'Ditolak')
                                                                            <span class="badge bg-danger">Ditolak</span>
                                                                        @else
                                                                            <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <form method="POST" action="{{ route('ta.acc', $item->id) }}" class="d-inline acc-form">
                                                                            @csrf
                                                                            <button type="button" class="btn btn-success btn-sm btn-acc"
                                                                                @if($item->status_revisi != 'Menunggu ACC') disabled @endif>
                                                                                ACC
                                                                            </button>
                                                                        </form>
                                                                        <button class="btn btn-warning btn-sm"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#revisiTAModal"
                                                                            data-id="{{ $item->id }}"
                                                                            @if($item->status_revisi != 'Menunggu ACC') disabled @endif>
                                                                            Revisi
                                                                        </button>
                                                                        @if(!empty($item->file_pdf))
                                                                            <a href="{{ asset('storage/' . $item->file_pdf) }}" target="_blank" class="btn btn-secondary btn-sm ms-1">
                                                                                <i class="bi bi-download"></i> Unduh File
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0">Belum ada laporan kemajuan.</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Revisi --}}
@include('admin.ta.modal.revisi')
@endsection

@push('scripts')
<!-- SweetAlert2 CDN jika belum ada di layout -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Modal revisi
    const modal = document.getElementById('revisiTAModal');
    if(modal){
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            modal.querySelector('#ta_id_input').value = id;
        });
    }

    // ACC dengan SweetAlert konfirmasi dan langsung tampil notif ACC tanpa reload
    $(document).on('click', '.btn-acc', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'ACC Revisi?',
            text: "Apakah Anda yakin ingin ACC revisi ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ACC!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX submit agar tidak reload
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Revisi telah di-ACC.',
                            confirmButtonColor: '#198754'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat ACC revisi.',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });

    // Notifikasi setelah ACC berhasil (jika pakai redirect biasa)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endpush