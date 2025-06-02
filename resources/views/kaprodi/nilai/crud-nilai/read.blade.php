<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1><i class="bi bi-check-circle-fill text-success me-2"></i>ACC Nilai Tugas Akhir</h1>
</div>

<div class="container">
    <ul class="nav nav-tabs nav-tabs-modern" id="nilaiTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                role="tab" aria-controls="semua" aria-selected="true">
                <i class="bi bi-list-ul me-2"></i>Semua Nilai
                <span class="badge bg-primary ms-2">{{ count($nilaiTAs) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="acc-tab" data-bs-toggle="tab" data-bs-target="#acc" type="button" role="tab"
                aria-controls="acc" aria-selected="false">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>Nilai Disetujui
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tolak-tab" data-bs-toggle="tab" data-bs-target="#tolak" type="button"
                role="tab" aria-controls="tolak" aria-selected="false">
                <i class="bi bi-x-circle-fill me-2 text-danger"></i>Nilai Ditolak
            </button>
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
                            <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Nilai Tugas Akhir</th>
                            <th scope="col"><i class="bi bi-info-circle me-2"></i>Status</th>
                            <th scope="col"><i class="bi bi-gear me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="nilaiTable">
                        @foreach ($nilaiTAs as $nilai)
                            <tr id="row-{{ $nilai->id }}" class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-circle me-2 fs-4"></i>
                                        <span>{{ $nilai->mahasiswa->nama ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="text-start">{{ $nilai->nilai }}</td>
                                <td id="status-{{ $nilai->id }}">
                                    @if ($nilai->status == 'Disetujui')
                                        <span class="badge bg-success"><i
                                                class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                    @elseif ($nilai->status == 'Ditolak')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @else
                                        <span class="badge bg-warning"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <form action="{{ url('/kaprodi/nilai/approve/' . $nilai->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm" type="submit" @if($nilai->status == 'Disetujui') disabled @endif>
                                                <i class="bi bi-check-lg me-1"></i>ACC
                                            </button>
                                        </form>
                                        <form action="{{ url('/kaprodi/nilai/reject/' . $nilai->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" type="submit" @if($nilai->status == 'Ditolak') disabled @endif>
                                                <i class="bi bi-x-lg me-1"></i>Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab ACC -->
        <div class="tab-pane fade" id="acc" role="tabpanel" aria-labelledby="acc-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead class="table-success">
                        <tr>
                            <th scope="col"><i class="bi bi-calendar-check-fill me-2"></i>Tanggal ACC</th>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Nilai</th>
                            <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="accTable">
                        @foreach ($nilaiTAs->where('status', 'Disetujui') as $nilai)
                            <tr class="align-middle">
                                <td>{{ \Carbon\Carbon::parse($nilai->tanggal_acc)->format('d-m-Y') ?? '-' }}</td>
                                <td class="text-start">{{ $nilai->nilai }}</td>
                                <td>{{ $nilai->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Ditolak -->
        <div class="tab-pane fade" id="tolak" role="tabpanel" aria-labelledby="tolak-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead class="table-danger">
                        <tr>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Nilai</th>
                            <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="tolakTable">
                        @foreach ($nilaiTAs->where('status', 'Ditolak') as $nilai)
                            <tr class="align-middle">
                                <td class="text-start">{{ $nilai->nilai }}</td>
                                <td>{{ $nilai->mahasiswa->nama ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
