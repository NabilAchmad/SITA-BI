\<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\ta\dashboard\dashboard.blade.php -->
@extends('layouts.template.main')

@section('title', 'Dashboard Tugas Akhir')

@section('content')
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1 text-primary"><i class="bi bi-mortarboard-fill me-2"></i> Dashboard Tugas Akhir</h1>
                <p class="text-muted mb-0">Kelola seluruh proses Tugas Akhir dari pengajuan hingga pemantauan progress.</p>
            </div>
        </div>

        {{-- Tabel Laporan Kemajuan Tugas Akhir --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary">Laporan Kemajuan Tugas Akhir</h5>
            </div>
            <div class="card-body">
                @if (isset($kemajuan) && $kemajuan->isEmpty())
                    <div class="alert alert-warning">Belum ada data kemajuan.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data dari database --}}
                                @foreach ($kemajuan as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td>
                                            @if ($item->status == 'acc')
                                                <span class="badge bg-success">ACC</span>
                                            @elseif ($item->status == 'tolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#revisiTAModal"
                                                data-id="{{ $item->id }}"
                                                @if($item->status != 'menunggu') disabled @endif>
                                                Revisi
                                            </button>
                                            <form method="POST" action="{{ route('ta.acc', $item->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm"
                                                    @if($item->status != 'menunggu') disabled @endif>
                                                    ACC
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('ta.tolak', $item->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-danger btn-sm"
                                                    @if($item->status != 'menunggu') disabled @endif>
                                                    Tolak
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- Contoh data statis jika ingin menampilkan contoh --}}
                                <tr>
                                    <td>01 Jun 2025</td>
                                    <td>Penyusunan Bab 1 dan Bab 2 selesai.</td>
                                    <td><span class="badge bg-warning text-dark">Menunggu ACC</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#revisiTAModal"
                                            data-id="1">
                                            Revisi
                                        </button>
                                        <form method="POST" action="{{ route('ta.acc', 1) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">ACC</button>
                                        </form>
                                        <form method="POST" action="{{ route('ta.tolak', 1) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15 Mei 2025</td>
                                    <td>Proposal telah direvisi sesuai masukan dosen.</td>
                                    <td><span class="badge bg-success">ACC</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" disabled>Revisi</button>
                                        <form method="POST" action="{{ route('ta.acc', 2) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm" disabled>ACC</button>
                                        </form>
                                        <form method="POST" action="{{ route('ta.tolak', 2) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" disabled>Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td>01 Mei 2025</td>
                                    <td>Pengajuan proposal tugas akhir.</td>
                                    <td><span class="badge bg-danger">Ditolak</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" disabled>Revisi</button>
                                        <form method="POST" action="{{ route('ta.acc', 3) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm" disabled>ACC</button>
                                        </form>
                                        <form method="POST" action="{{ route('ta.tolak', 3) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" disabled>Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                {{-- End contoh data --}}
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

@push('styles')
    <style>
        .dashboard-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.07);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endpush

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