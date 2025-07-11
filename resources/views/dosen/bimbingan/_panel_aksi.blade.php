{{-- Panel Status --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                Status TA
                <span class="badge bg-info rounded-pill">{{ ucfirst(str_replace('_', ' ', $tugasAkhir->status)) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                Sesi Bimbingan
                <span class="badge bg-primary rounded-pill">{{ $bimbinganCount }} / 7 <small>(min)</small></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                Kelayakan Sidang
                {{-- Logika untuk status kelayakan sidang --}}
                <span class="badge bg-warning text-dark rounded-pill">Belum Disetujui</span>
            </li>
        </ul>
    </div>
</div>

{{-- Panel Aksi Keputusan --}}
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-ui-checks-grid me-2 text-success"></i>Aksi & Keputusan</h5>
        <div class="d-grid gap-2">

            {{-- ✅ PERBAIKAN: Tombol ini sekarang memicu modal, bukan pindah halaman --}}
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBuatJadwal">
                <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Bimbingan Baru
            </button>

            <hr>

            {{-- Tombol ini akan muncul bersyarat, misalnya jika draf final sudah diunggah --}}
            @if ($tugasAkhir->status === 'bimbingan_final')
                <h6 class="text-muted text-center small my-2">Persetujuan Sidang</h6>

                {{-- Logika untuk menampilkan tombol persetujuan Dospem 1 atau Dospem 2 --}}
                @if (auth()->user()->dosen->id === $tugasAkhir->pembimbingSatu->dosen_id &&
                        $tugasAkhir->status_acc_dospem1 != 'disetujui')
                    <form action="{{ route('dosen.sidang.setujui', $tugasAkhir->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-1"></i> Beri Persetujuan Sidang (P1)
                        </button>
                    </form>
                @elseif (auth()->user()->dosen->id === $tugasAkhir->pembimbingDua->dosen_id &&
                        $tugasAkhir->status_acc_dospem1 == 'disetujui' &&
                        $tugasAkhir->status_acc_dospem2 != 'disetujui')
                    <form action="{{ route('dosen.sidang.setujui', $tugasAkhir->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle-fill me-1"></i> Beri Persetujuan Sidang (P2)
                        </button>
                    </form>
                @else
                    <button class="btn btn-outline-secondary w-100" disabled>Menunggu Aksi Lain</button>
                @endif
            @else
                <button class="btn btn-outline-secondary" disabled>Tindakan Belum Tersedia</button>
            @endif
        </div>
    </div>
</div>
