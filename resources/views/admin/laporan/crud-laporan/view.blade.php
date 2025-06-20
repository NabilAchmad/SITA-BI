<div class="container-fluid">
    <h1 class="mb-4 fw-bold">
        <i class="bi bi-bar-chart-line me-2 text-primary"></i> Laporan dan Statistik
    </h1>
<div class="container">
    <h1 class="mb-4">Laporan dan Statistik</h1>

    {{-- CHARTS SECTION --}}
    <div class="row g-4 mb-5">
        <!-- Mahasiswa per Prodi -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Mahasiswa per Prodi</h6>
                <canvas id="prodiChart" height="200"></canvas>
            </div>
        </div>

        <!-- Mahasiswa per Status -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Mahasiswa per Status</h6>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>

        <!-- Status Sidang per Jenis -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Status Sidang per Jenis</h6>
                <canvas id="sidangChart" height="200"></canvas>
            </div>
        </div>

        <!-- Similarity Score Distribution -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Distribusi Skor Similarity</h6>
                <canvas id="similarityChart" height="200"></canvas>
            </div>
        </div>

        <!-- Status Revisi TA -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Status Revisi Tugas Akhir</h6>
                <canvas id="revisiChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- TABLES SECTION --}}
    <div class="row g-4">
        <!-- Jumlah Mahasiswa per Angkatan -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Jumlah Mahasiswa per Angkatan</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Angkatan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswaPerAngkatan as $item)
                            <tr>
                                <td>{{ $item->angkatan }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                        @empty <tr>
                                <td colspan="2">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dokumen TA Statistik -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Dokumen Tugas Akhir</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dokumenStatistik as $item)
                            <tr>
                                <td>{{ ucfirst($item->tipe_dokumen) }}</td>
                                <td>{{ ucfirst($item->status_validasi) }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                        @empty <tr>
                                <td colspan="3">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alumni Summary -->
    <div class="mt-4">
        <div class="alert alert-success">
            <h5 class="mb-0">Total Alumni Terdaftar: <strong>{{ $totalAlumni }}</strong> mahasiswa</h5>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prodi Chart
            new Chart(document.getElementById('prodiChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($mahasiswaPerProdi->pluck('prodi')) !!},
                    datasets: [{
                        label: 'Jumlah',
                        data: {!! json_encode($mahasiswaPerProdi->pluck('total')) !!},
                        backgroundColor: '#6366f1'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Status Chart
            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($mahasiswaPerStatus->pluck('status')) !!},
                    datasets: [{
                        data: {!! json_encode($mahasiswaPerStatus->pluck('total')) !!},
                        backgroundColor: ['#4ade80', '#60a5fa', '#facc15', '#f87171']
                    }]
                }
            });

            // Sidang Chart
            const sidang = {!! json_encode($sidangStatistik->groupBy('jenis_sidang')) !!};
            const sidangLabels = Object.keys(sidang);
            const sidangStatus = ['menunggu', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus'];
            const sidangColors = {
                menunggu: '#facc15',
                dijadwalkan: '#60a5fa',
                lulus: '#4ade80',
                lulus_revisi: '#38bdf8',
                tidak_lulus: '#f87171'
            };
            const sidangDatasets = sidangStatus.map(status => ({
                label: status.replace('_', ' '),
                data: sidangLabels.map(jenis => {
                    const found = sidang[jenis].find(s => s.status === status);
                    return found ? found.total : 0;
                }),
                backgroundColor: sidangColors[status]
            }));
            new Chart(document.getElementById('sidangChart'), {
                type: 'bar',
                data: {
                    labels: sidangLabels,
                    datasets: sidangDatasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Similarity Chart
            new Chart(document.getElementById('similarityChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($similarityStat->pluck('kategori')) !!},
                    datasets: [{
                        label: 'Jumlah',
                        data: {!! json_encode($similarityStat->pluck('total')) !!},
                        backgroundColor: '#f97316'
                    }]
                }
            });

            // Revisi Chart
            new Chart(document.getElementById('revisiChart'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($revisiStatus->pluck('status_revisi')) !!},
                    datasets: [{
                        data: {!! json_encode($revisiStatus->pluck('total')) !!},
                        backgroundColor: ['#60a5fa', '#f87171']
                    }]
                }
            });
        });
    </script>
@endpush
    <div class="mb-5">
        <h4>Jumlah Mahasiswa per Prodi</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Prodi</th>
                    <th>Total Mahasiswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswaPerProdi as $item)
                    <tr>
                        <td>{{ $item->prodi }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <h4>Status Sidang Mahasiswa</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Status Sidang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sidangPerStatus as $item)
                    <tr>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
