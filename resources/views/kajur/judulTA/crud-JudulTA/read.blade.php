<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h1 class="text-center mb-4 fw-bold text-primary">ACC Judul Tugas Akhir</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle" style="border-radius: 8px; overflow: hidden;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="align-middle py-3">Nama Mahasiswa</th>
                                    <th class="align-middle py-3">Judul Tugas Akhir</th>
                                    <th class="align-middle py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody id="judulTable">
                                @foreach ($judulTAs as $judul)
                                    <tr class="align-items-center">
                                        <td class="py-3">{{ $judul->mahasiswa->nama ?? 'N/A' }}</td>
                                        <td class="py-3">{{ $judul->judul }}</td>
                                        <td id="status-{{ $judul->id }}" class="py-3">
                                            @if ($judul->status == 'Disetujui')
                                                <span class="badge bg-success rounded-pill px-3 py-2">Disetujui</span>
                                            @elseif ($judul->status == 'Ditolak')
                                                <span class="badge bg-danger rounded-pill px-3 py-2">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning rounded-pill px-3 py-2">Menunggu</span>
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
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="card-title mb-0 fw-bold">Judul ACC</h3>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle" id="accTable" style="border-radius: 8px; overflow: hidden;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="align-middle py-3">Tanggal ACC</th>
                                    <th class="align-middle py-3">Judul Tugas Akhir</th>
                                    <th class="align-middle py-3">Pengaju</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($judulTAs->where('status', 'Disetujui') as $accJudul)
                                    <tr id="acc-row-{{ $accJudul->id }}" class="align-items-center">
                                        <td class="py-3">{{ $accJudul->tanggal_acc ? $accJudul->tanggal_acc->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="py-3">{{ $accJudul->judul }}</td>
                                        <td class="py-3">{{ $accJudul->mahasiswa->nama ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>