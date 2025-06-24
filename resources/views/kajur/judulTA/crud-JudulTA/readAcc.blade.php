<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1 class="display-5 fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Judul Tugas Akhir yang Disetujui</h1>
</div>
<div class="container mb-4">
    <a href="{{ route('kajur.judul.page') }}" class="btn btn-secondary btn-lg shadow-sm hover-scale">
        <i class="bi bi-arrow-left"></i> Kembali ke Semua Judul
    </a>
</div>

<div class="container">
    <div class="table-responsive rounded-4 shadow-sm">
        <table class="table table-bordered table-hover text-center align-middle custom-table mb-0">
            <thead class="table-success">
                <tr>
                    <th scope="col" class="py-3"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                    <th scope="col" class="py-3"><i class="bi bi-file-earmark-text me-2"></i>Judul Tugas Akhir</th>
                    <th scope="col" class="py-3"><i class="bi bi-info-circle me-2"></i>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($judulTAs as $judul)
                    <tr class="align-middle hover-row">
                        <td class="py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-circle me-2 fs-4"></i>
                                <span class="fw-medium">{{ $judul->mahasiswa->nama ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-start py-3">{{ $judul->judul }}</td>
                        <td class="py-3">
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .custom-table {
        border: none;
    }

    .custom-table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 2px solid #e9ecef;
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }

    .hover-row {
        transition: background-color 0.2s ease;
    }

    .hover-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table td, .table th {
        border-color: #e9ecef;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .section-title h1 {
        margin-bottom: 1.5rem;
        border-bottom: 3px solid #198754;
        display: inline-block;
        padding-bottom: 0.5rem;
    }
</style>