@extends('layouts.template.kaprodi')

@section('content')
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1><i class="bi bi-check-circle-fill text-success me-2"></i>ACC Judul Tugas Akhir</h1>
    </div>

    <div class="container">
        <ul class="nav nav-tabs nav-tabs-modern nav-tabs-hover-effect" id="judulTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                    role="tab" aria-controls="semua" aria-selected="true">
                    <i class="bi bi-list-ul me-2"></i>Semua Judul
                    <span class="badge bg-primary rounded-pill ms-2">{{ count($judulTAs) }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="acc-tab" href="{{ route('kaprodi.judul.acc') }}" role="tab" aria-selected="false">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>Judul Disetujui
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tolak-tab" href="{{ route('kaprodi.judul.tolak') }}" role="tab"
                    aria-controls="tolak" aria-selected="false">
                    <i class="bi bi-x-circle-fill me-2 text-danger"></i>Judul Ditolak
                </a>
            </li>
        </ul>

        <div class="tab-content pt-4">
            <!-- Tab Semua -->
            <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle custom-table animate__animated animate__fadeIn">
                        <thead class="table-gradient">
                            <tr>
                                <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                                <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Judul Tugas Akhir</th>
                                <th scope="col"><i class="bi bi-info-circle me-2"></i>Status</th>
                                {{-- <th scope="col"><i class="bi bi-gear me-2"></i>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody id="judulTable">
                            @foreach ($judulTAs as $judul)
                                <tr id="row-{{ $judul->id }}" class="align-middle table-row-hover">
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="avatar-circle-modern me-2">
                                                <i class="bi bi-person-circle fs-4"></i>
                                            </div>
                                            <span class="fw-medium">{{ $judul->mahasiswa->nama ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <a href="javascript:void(0);" onclick="showDetail({{ $judul->id }})"
                                            class="text-decoration-none text-dark hover-primary-modern">
                                            {{ $judul->judul }}
                                        </a>
                                    </td>
                                    <td id="status-{{ $judul->id }}">
                                        @if ($judul->status == 'disetujui')
                                            <span class="badge status-badge success"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                        @elseif ($judul->status == 'Ditolak')
                                            <span class="badge status-badge danger"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                        @else
                                            <span class="badge status-badge warning"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-modern btn-floating" onclick="accJudul({{ $judul->id }})"
                                                @if($judul->status == 'Disetujui') disabled @endif>
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-danger btn-modern btn-floating" onclick="tolakJudul({{ $judul->id }})"
                                                @if($judul->status == 'Ditolak') disabled @endif>
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </td> --}}
                                </tr>
                            @endforeach
                            <tr id="detail-row" style="display:none;">
                                <td colspan="4">
                                    <div class="card modern-card animate__animated animate__fadeInRight" id="detail-card">
                                        <div class="card-header modern-card-header">
                                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detail Judul Tugas Akhir</h5>
                                        </div>
                                        <div class="card-body modern-card-body">
                                            <div class="info-group">
                                                <label class="info-label">Nama Pengaju</label>
                                                <p class="info-content" id="detail-nama"></p>
                                            </div>
                                            <div class="info-group">
                                                <label class="info-label">Judul Tugas Akhir</label>
                                                <p class="info-content" id="detail-judul"></p>
                                            </div>
                                            <div class="info-group">
                                                <label class="info-label">Judul-Judul Mirip</label>
                                                <div class="similar-list-modern">
                                                    <ul id="detail-similar-list" class="list-unstyled mb-0"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer modern-card-footer">
                                            <button class="btn btn-secondary btn-modern" onclick="hideDetail()">
                                                <i class="bi bi-x me-1"></i>Tutup
                                            </button>
                                            <button class="btn btn-success btn-modern" id="detail-acc-btn" onclick="accJudulFromDetail()">
                                                <i class="bi bi-check-lg me-1"></i>ACC
                                            </button>
                                            <button class="btn btn-danger btn-modern" id="detail-tolak-btn" onclick="tolakJudulFromDetail()">
                                                <i class="bi bi-check-lg me-1">Tolak</i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
    .table-gradient {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
    }

    .table-row-hover:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-modern {
        font-weight: 500;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        padding: 0.6rem 1.2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-floating {
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .avatar-circle-modern {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .status-badge {
        padding: 0.6rem 1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .status-badge.success {
        background: linear-gradient(135deg, #43a047 0%, #66bb6a 100%);
        color: white;
    }

    .status-badge.danger {
        background: linear-gradient(135deg, #d32f2f 0%, #ef5350 100%);
        color: white;
    }

    .status-badge.warning {
        background: linear-gradient(135deg, #fb8c00 0%, #ffa726 100%);
        color: white;
    }

    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        overflow: hidden;
        position: fixed;
        top: 80px;
        right: 20px;
        width: 400px;
        z-index: 1050;
    }

    .modern-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 1.2rem;
        border-bottom: none;
    }

    .modern-card-body {
        padding: 1.5rem;
    }

    .modern-card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,0.1);
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.8rem;
    }

    .info-group {
        margin-bottom: 1.5rem;
    }

    .info-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .info-content {
        font-weight: 600;
        color: #2a2a2a;
        margin: 0;
        line-height: 1.5;
    }

    .similar-list-modern {
        max-height: 200px;
        overflow-y: auto;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    .nav-tabs-hover-effect .nav-link {
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-tabs-hover-effect .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #0d6efd;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-tabs-hover-effect .nav-link:hover::after {
        width: 100%;
    }

    .nav-tabs-hover-effect .nav-link.active::after {
        width: 100%;
    }

    .hover-primary-modern {
        transition: all 0.3s ease;
    }

    .hover-primary-modern:hover {
        color: #0d6efd !important;
        text-decoration: underline !important;
    }

    .custom-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
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

            function showDetail(id) {
                let uri = "{{ route('kaprodi.judul.similar', ['id' => ':id']) }}".replace(':id', id);
                $.ajax({
                    url: uri,
                    type: 'GET',
                    success: function (response) {
                        $('#detail-nama').text(response.nama_pengaju);
                        $('#detail-judul').text(response.judul_ta);
                        $('#detail-similar-list').empty();
                        if (response.similar_juduls.length > 0) {
                            response.similar_juduls.forEach(function (judul) {
                                $('#detail-similar-list').append('<li>' + judul + '</li>');
                            });
                        } else {
                            $('#detail-similar-list').append('<li>Tidak ada judul mirip ditemukan.</li>');
                        }
                        $('#detail-row').show();
                        // Scroll to detail card
                        $('html, body').animate({
                            scrollTop: $('#detail-row').offset().top
                        }, 500);
                    },
                    error: function () {
                        alert('Gagal mengambil data detail.');
                    }
                });
            }

            function hideDetail() {
                $('#detail-row').hide();
            }

            // Acc Judul
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

            // tolak judul
            function tolakJudul(id) {
                let uri = "{{route('kaprodi.judul.reject', ['id' => ':id'])}}".replace(':id', id);
                $.ajax({
                    url: uri, // pastikan ini route POST
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        if (response.success) {
                            alert('Judul berhasil di-Tolak');
                            location.reload(); // Refresh halaman atau update tampilan
                        } else {
                            alert('Gagal menolak judul');
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            } 

            function accJudulFromDetail() {
                // Get the id of the current detail shown
                let id = $('#detail-row').prev('tr').attr('id'); // This gets the previous tr id like "row-123"
                if (!id) {
                    alert('ID Judul tidak ditemukan.');
                    return;
                }
                id = id.replace('row-', '');
                accJudul(id);
            }
            function tolakJudulFromDetail() {
                // Get the id of the current detail shown
                let id = $('#detail-row').prev('tr').attr('id'); // This gets the previous tr id like "row-123"
                if (!id) {
                    alert('ID Judul tidak ditemukan.');
                    return;
                }
                id = id.replace('row-', '');
                tolakJudul(id);
            }
            </script>
    @endsection
@endsection