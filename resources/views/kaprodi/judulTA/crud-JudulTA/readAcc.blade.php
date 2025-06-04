<!-- Section Title -->

<div class="container section-title" data-aos="fade-up">
    <h1><i class="bi bi-check-circle-fill text-success me-2"></i>Judul Tugas Akhir yang Disetujui</h1>
</div>

<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle custom-table">
            <thead class="table-success">
                <tr>
                    <th scope="col"><i class="bi bi-person-badge me-2"></i>Nama Mahasiswa</th>
                    <th scope="col"><i class="bi bi-file-earmark-text me-2"></i>Judul Tugas Akhir</th>
                    <th scope="col"><i class="bi bi-info-circle me-2"></i>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($judulTAs as $judul)
                    <tr class="align-middle">
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-circle me-2 fs-4"></i>
                                <span>{{ $judul->mahasiswa->nama ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-start">{{ $judul->judul }}</td>
                        <td>
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
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
</style>
