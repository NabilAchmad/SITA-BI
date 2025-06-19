<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1 class="modern-title"><i class="bi bi-check-circle-fill text-success me-2 pulse-icon"></i>ACC Nilai Tugas Akhir</h1>
</div>

<div class="container glass-container">
    <ul class="nav nav-tabs nav-tabs-modern" id="nilaiTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active hover-effect glass-button" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                role="tab" aria-controls="semua" aria-selected="true">
                <i class="bi bi-list-ul me-2 icon-bounce"></i>Semua Nilai
                <span class="badge bg-primary ms-2 badge-pulse">{{ count($nilais) }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content pt-4">
        <!-- Tab Semua -->
        <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle custom-table animate-table">
                    <thead class="table-dark glass-header">
                        <tr>
                            <th scope="col"><i class="bi bi-person-badge me-2 icon-bounce"></i>Nama Mahasiswa</th>
                            <th scope="col"><i class="bi bi-file-earmark-text me-2 icon-bounce"></i>Nilai Tugas Akhir</th>
                            <th scope="col"><i class="bi bi-info-circle me-2 icon-bounce"></i>Status</th>
                            <th scope="col"><i class="bi bi-award me-2 icon-bounce"></i>Nilai Akhir</th>
                            <th scope="col"><i class="bi bi-patch-check-fill me-2 icon-bounce"></i>Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody id="nilaiTable">
                        @foreach ($nilais as $nilai)
                            <tr id="row-{{ $nilai->id }}" class="align-middle hover-row glass-row">
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-circle me-2 fs-4 profile-icon"></i>
                                        <span class="fw-bold">{{ $nilai->mahasiswa->nama ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="text-start fw-bold">{{ $nilai->nilai }}</td>
                                <td id="status-{{ $nilai->id }}">
                                    @if ($nilai->status == 'Disetujui')
                                        <span class="badge bg-success status-badge glass-badge"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                    @elseif ($nilai->status == 'Ditolak')
                                        <span class="badge bg-danger status-badge glass-badge"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @else
                                        <span class="badge bg-warning status-badge glass-badge"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                    @endif
                                </td>
                                <td class="text-start fw-bold">{{ $nilai->nilai_angka ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusAkhir = $nilai->tugasAkhir->status ?? 'N/A';
                                    @endphp
                                    @if ($statusAkhir == 'lulus')
                                        <span class="badge bg-success status-badge glass-badge"><i class="bi bi-check-circle-fill me-1"></i>Lulus</span>
                                    @elseif ($statusAkhir == 'lulus dengan revisi')
                                        <span class="badge bg-warning status-badge glass-badge"><i class="bi bi-exclamation-triangle-fill me-1"></i>Lulus dengan Revisi</span>
                                    @elseif ($statusAkhir == 'tidak lulus')
                                        <span class="badge bg-danger status-badge glass-badge"><i class="bi bi-x-circle-fill me-1"></i>Tidak Lulus</span>
                                    @else
                                        <span class="badge bg-secondary status-badge glass-badge">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .modern-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1a237e;
        margin-bottom: 2rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        letter-spacing: -1px;
        background: linear-gradient(45deg, #1a237e, #283593);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glass-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .nav-tabs-modern {
        border-bottom: none;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .glass-button {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1rem 2rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        transition: all 0.3s ease;
    }

    .glass-button:hover, .glass-button.active {
        background: rgba(13, 110, 253, 0.9);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
    }

    .custom-table {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    .glass-header th {
        background: linear-gradient(45deg, #1a237e, #283593);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1.5rem;
        border: none;
    }

    .glass-row {
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }

    .glass-row:hover {
        transform: scale(1.01);
        background: rgba(13, 110, 253, 0.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .glass-badge {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .icon-bounce {
        animation: bounce 1.5s ease infinite;
    }

    .profile-icon {
        color: #1a237e;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .profile-icon:hover {
        transform: scale(1.2) rotate(5deg);
        color: #283593;
    }

    .animate-table {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .badge-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .hover-effect {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
</style>
