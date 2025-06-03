<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\ta\dashboard\dashboard.blade.php -->
@extends('layouts.template.main')

@section('title', 'Dashboard Sidang Mahasiswa')

@section('content')
<div class="container-fluid">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1 text-primary"><i class="bi bi-mortarboard-fill me-2"></i> Dashboard Sidang Mahasiswa</h1>
            <p class="text-muted mb-0">Lihat daftar mahasiswa yang sidang dan detail laporan kemajuan tugas akhirnya.</p>
        </div>
    </div>

    {{-- List Mahasiswa Sidang --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-primary">Daftar Mahasiswa Sidang</h5>
        </div>
        <div class="card-body">
            @if(isset($mahasiswaSidang) && $mahasiswaSidang->isEmpty())
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
                            @foreach ($mahasiswaSidang as $mhs)
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
                                                                        <form method="POST" action="{{ route('ta.acc', $item->id) }}" class="d-inline">
                                                                            @csrf
                                                                            <button class="btn btn-success btn-sm"
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
<script>
    const modal = document.getElementById('revisiTAModal');
    if(modal){
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            modal.querySelector('#ta_id_input').value = id;
        });
    }
</script>
@endpush