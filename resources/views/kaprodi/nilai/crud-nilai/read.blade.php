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
                            <th scope="col"><i class="bi bi-pencil-square me-2 icon-bounce"></i>Aksi</th>
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
                                <td>
                                    <a href="{{ route('kaprodi.nilai.edit', $nilai->id) }}" class="btn btn-primary btn-sm">
                                        Isi Nilai
                                    </a>
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
    /* Styles same as kajur, omitted for brevity */
</style>
