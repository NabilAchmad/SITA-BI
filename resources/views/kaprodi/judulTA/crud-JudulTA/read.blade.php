@extends('layouts.template.kaprodi')

@section('content')
<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1><i class="bi bi-check-circle-fill text-success me-2"></i>ACC Judul Tugas Akhir</h1>
</div>

<div class="container">
    <ul class="nav nav-tabs nav-tabs-modern" id="judulTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                role="tab" aria-controls="semua" aria-selected="true">
                <i class="bi bi-list-ul me-2"></i>Semua Judul
                <span class="badge bg-primary ms-2">{{ count($judulTAs) }}</span>
            </button>
        </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="acc-tab" href="{{ route('kaprodi.judul.acc') }}" role="tab" aria-selected="false">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>Judul Disetujui
                </a>
            </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tolak-tab" href="{{ route('kaprodi.judul.tolak') }}" role="tab" aria-controls="tolak" aria-selected="false">
                <i class="bi bi-x-circle-fill me-2 text-danger"></i>Judul Ditolak
            </a>
        </li>
    </ul>

    <div class="tab-content pt-4">
        <!-- Tab Semua -->
        <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Judul Tugas Akhir</th>
                            <th scope="col"><i class="bi bi-info-circle me-2"></i>Status</th>
                            <th scope="col"><i class="bi bi-gear me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="judulTable">
                        @foreach ($judulTAs as $judul)
                            <tr id="row-{{ $judul->id }}" class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-circle me-2 fs-4"></i>
                                        <span>{{ $judul->mahasiswa->nama ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="text-start">{{ $judul->judul }}</td>
                                <td id="status-{{ $judul->id }}">
                                    @if ($judul->status == 'disetujui')
                                        <span class="badge bg-success"><i
                                                class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                    @elseif ($judul->status == 'Ditolak')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @else
                                        <span class="badge bg-warning"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-success btn-sm" onclick="accJudul({{ $judul->id }})"
                                            @if($judul->status == 'Disetujui') disabled @endif>
                                            <i class="bi bi-check-lg me-1"></i>ACC
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="tolakJudul({{ $judul->id }})"
                                            @if($judul->status == 'Ditolak') disabled @endif>
                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        

        <!-- Tab Ditolak -->
        {{-- <div class="tab-pane fade" id="tolak" role="tabpanel" aria-labelledby="tolak-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead class="table-danger">
                        <tr>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Judul</th>
                            <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="tolakTable">
                        <!-- Baris Ditolak ditambahkan lewat JS -->
                    </tbody>
                </table>
            </div>
        </div> --}}
    </div>
</div>

<style>
    .nav-tabs-modern {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        color: #6c757d;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs-modern .nav-link:hover {
        color: #0d6efd;
        border: none;
    }

    .nav-tabs-modern .nav-link.active {
        color: #0d6efd;
        border: none;
        border-bottom: 2px solid #0d6efd;
        margin-bottom: -2px;
    }

    .custom-table {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .custom-table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-group .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
</style>

@section('script')
    <script>
        $(document).ready(function () {
            // Setup CSRF Token untuk semua request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Ketika tombol ACC diklik
            $('.btn-acc').on('click', function () {

            });
        });
        function accJudul(id) {
            let uri = "{{route('kaprodi.judul.approve', ['id' => ':id'])}}".replace(':id', id);
            $.ajax({
                url: uri, // pastikan ini route POST
                type: 'POST',
                data: { id: id },
                success: function (response) {
                    if (response.success) {
                        alert('Judul berhasil di-ACC');
                        location.reload(); // Refresh halaman atau update tampilan
                    } else {
                        alert('Gagal meng-ACC judul');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }
    </script>
@endsection
@endsection