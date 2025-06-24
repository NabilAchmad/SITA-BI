@extends('layouts.template.kaprodi')

@section('content')
<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1><i class="bi bi-x-circle-fill text-danger me-2"></i>Judul Tugas Akhir Ditolak</h1>
</div>

<div class="container mb-4">
    <a href="{{ route('kajur.judul.page') }}" class="btn btn-secondary btn-hover-effect">
        <i class="bi bi-arrow-left"></i> Kembali ke Semua Judul
    </a>
</div>

<div class="container">
    <div class="table-responsive card-table">
        <table class="table table-bordered table-hover text-center align-middle custom-table">
            <thead class="table-danger">
                <tr>
                    <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                    <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Judul Tugas Akhir</th>
                    <th scope="col"><i class="bi bi-info-circle me-2"></i>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($judulTolak as $judul)
                    <tr class="align-middle hover-row">
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="profile-icon me-2">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <span class="fw-medium">{{ $judul->mahasiswa->nama ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-start">{{ $judul->judul }}</td>
                        <td>
                            <span class="badge bg-danger status-badge"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .custom-table {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }

    .custom-table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.05);
    }

    .card-table {
        background: white;
        border-radius: 12px;
        padding: 1rem;
    }

    .hover-row:hover {
        transform: translateY(-1px);
        transition: all 0.3s ease;
    }

    .profile-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.05);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .btn-hover-effect {
        transition: all 0.3s ease;
    }

    .btn-hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .table td, .table th {
        padding: 1rem;
    }

    .fw-medium {
        font-weight: 500;
    }
</style>
@endsection