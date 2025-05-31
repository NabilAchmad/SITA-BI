<div class="container-fluid">
    <h1 class="mb-4 fw-bold"><i class="bi bi-bar-chart-line me-2 text-primary"></i> Laporan dan Statistik</h1>

    {{-- CHARTS --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Mahasiswa per Prodi</h6>
                <canvas id="prodiChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Mahasiswa per Status</h6>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Status Sidang per Jenis</h6>
                <canvas id="sidangChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="row g-4">
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
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="fw-semibold mb-3">Jumlah Dokumen Tugas Akhir</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Status Validasi</th>
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

    <div class="mt-4">
        <div class="alert alert-success">
            <h5 class="mb-0">Total Alumni Terdaftar: <strong>{{ $totalAlumni }}</strong> mahasiswa</h5>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === CHART DATA ===
            const prodiLabels = {!! json_encode(
                $mahasiswaPerProdi->pluck('prodi')->map(function($prodi) {
                    if (strtolower($prodi) === 'd4') return 'D4 Bahasa Inggris';
                    if (strtolower($prodi) === 'd3') return 'D3 Bahasa Inggris';
                    return $prodi;
                })
            ) !!};
            const prodiData = {!! json_encode($mahasiswaPerProdi->pluck('total')) !!};

            const statusLabels = {!! json_encode($mahasiswaPerStatus->pluck('status')) !!};
            const statusData = {!! json_encode($mahasiswaPerStatus->pluck('total')) !!};

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

            // === CHART INSTANCES ===
            new Chart(document.getElementById('prodiChart'), {
                type: 'bar',
                data: {
                    labels: prodiLabels,
                    datasets: [{
                        label: 'Jumlah',
                        data: prodiData,
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

            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#4ade80', '#60a5fa', '#facc15', '#f87171']
                    }]
                }
            });

            const sidangDatasets = sidangStatus.map(stat => ({
                label: stat.replace('_', ' '),
                data: sidangLabels.map(jenis => {
                    const entry = sidang[jenis].find(s => s.status === stat);
                    return entry ? entry.total : 0;
                }),
                backgroundColor: sidangColors[stat]
            }));

            new Chart(document.getElementById('sidangChart'), {
                type: 'bar',
                data: {
                    labels: sidangLabels.map(s => s.toUpperCase()),
                    datasets: sidangDatasets
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
