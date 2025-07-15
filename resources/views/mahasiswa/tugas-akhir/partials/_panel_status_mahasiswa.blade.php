{{-- Panel Status & Progress --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            @if ($pembimbing1 && $pembimbing1->dosen && $pembimbing1->dosen->user)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check-fill me-2"></i>P1:
                        {{ Str::limit($pembimbing1->dosen->user->name ?? '-', 50) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP1 }}
                        / 7</span>
                </li>
            @endif
            @if ($pembimbing2 && $pembimbing2->dosen && $pembimbing2->dosen->user)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check me-2"></i>P2:
                        {{ Str::limit($pembimbing2->dosen->user->name ?? '-', 50) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP2 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP2 }}
                        / 7</span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span>Kelayakan Sidang Akhir</span>
                @php
                    $statusKelayakan = match ($tugasAkhir->status) {
                        'menunggu_acc_p1' => ['text' => 'Menunggu P1', 'class' => 'bg-warning text-dark'],
                        'menunggu_acc_p2' => ['text' => 'Menunggu P2', 'class' => 'bg-info'],
                        'layak_sidang' => ['text' => 'Layak Sidang', 'class' => 'bg-success'],
                        default => ['text' => 'Belum Diajukan', 'class' => 'bg-secondary'],
                    };
                @endphp
                <span class="badge {{ $statusKelayakan['class'] }} rounded-pill">{{ $statusKelayakan['text'] }}</span>
            </li>
        </ul>
    </div>
</div> --}}

{{-- Panel Jadwal Aktif --}}

@forelse ($jadwalAktif as $jadwal)
    <div class="card shadow-lg border-0 rounded-4 mb-4 overflow-hidden">
        <!-- Header dengan gradient -->
        <div class="card-header bg-gradient position-relative"
            style="background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="bi bi-calendar-check me-2"></i>
                    Jadwal Bimbingan Aktif
                </h5>
                <span class="badge bg-light text-primary fw-bold fs-6 px-3 py-2 rounded-pill">
                    Sesi ke-{{ $jadwal->sesi_ke }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            @if ($jadwal->status_bimbingan === 'dijadwalkan')
                <!-- Status Dikonfirmasi -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-check-circle-fill text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h6 class="fw-bold text-success mb-1">Jadwal Dikonfirmasi</h6>
                        <p class="mb-0 text-muted">
                            Jadwal bimbingan Anda dengan <strong
                                class="text-primary">{{ $jadwal->dosen->user->name }}</strong> telah dikonfirmasi
                        </p>
                    </div>
                </div>

                <!-- Informasi Jadwal -->
                <div class="alert alert-success border-0 rounded-3 mb-3"
                    style="background: linear-gradient(135deg, #f2ffeb 0%, #d0ffd7 100%);">
                    <div class="row text-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <i class="bi bi-calendar3 text-primary fs-4 d-block mb-2"></i>
                            <div class="fw-bold text-dark fs-5">
                                {{ optional($jadwal->tanggal_bimbingan)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-clock text-primary fs-4 d-block mb-2"></i>
                            <div class="fw-bold text-dark fs-5">
                                {{ \Carbon\Carbon::parse($jadwal->jam_bimbingan)->format('H:i') }} WIB
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#modalUbahJadwal">
                        <i class="bi bi-journal-text me-2"></i>Ajukan Perubahan Jadwal
                    </button>
                </div>
            @else
                <!-- Status Menunggu -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-hourglass-split text-warning fs-3"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h6 class="fw-bold text-warning mb-1">Menunggu Konfirmasi</h6>
                        <p class="mb-0 text-muted">
                            Menunggu konfirmasi jadwal bimbingan Sesi ke-{{ $jadwal->sesi_ke }} dari <strong
                                class="text-primary">{{ $jadwal->dosen->user->name }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Status Waiting -->
                <div class="alert alert-warning border-0 rounded-3 mb-3"
                    style="background: linear-gradient(135deg, #fff3cd 0%, #fef3c7 100%);">
                    <div class="text-center">
                        <div class="spinner-border text-warning mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="fw-bold text-warning fs-5">
                            Proses Konfirmasi Sedang Berlangsung
                        </div>
                        <small class="text-muted d-block mt-2">
                            Dosen pembimbing akan segera mengkonfirmasi jadwal Anda
                        </small>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="text-center">
                    <button class="btn btn-outline-warning rounded-pill px-4">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh Status
                    </button>
                </div>
            @endif

            <!-- Informasi Tambahan -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-md-4 text-center mb-2">
                    <i class="bi bi-person-badge text-primary fs-5"></i>
                    <div class="small text-muted mt-1">Pembimbing</div>
                </div>
                <div class="col-md-4 text-center mb-2">
                    <i class="bi bi-hash text-primary fs-5"></i>
                    <div class="small text-muted mt-1">Sesi Ke-{{ $jadwal->sesi_ke }}</div>
                </div>
                <div class="col-md-4 text-center mb-2">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    <div class="small text-muted mt-1">Status: {{ ucfirst($jadwal->status_bimbingan) }}</div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-body text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                style="width: 80px; height: 80px;">
                <i class="bi bi-moon-stars text-muted" style="font-size: 2.5rem;"></i>
            </div>
            <h5 class="text-muted fw-bold mb-3">Tidak Ada Jadwal Aktif</h5>
            <p class="text-muted mb-4">
                Saat ini tidak ada jadwal bimbingan yang sedang aktif. <br>
                Silakan ajukan jadwal bimbingan baru dengan dosen pembimbing Anda.
            </p>
            <button class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Jadwal Baru
            </button>
        </div>
    </div>
@endforelse


{{-- Syarat Panel Pendaftaran Sidang --}}
@php
    // ==================================================================
    // == PERBAIKAN FINAL: Logika perhitungan syarat dipindahkan ke sini ==
    // ==================================================================
    $syaratJumlahBimbinganTerpenuhi = false;
    if (isset($bimbinganCountP1) && isset($bimbinganCountP2)) {
        // Jika ada dua pembimbing
        $syaratJumlahBimbinganTerpenuhi = ($bimbinganCountP1 >= 7 && $bimbinganCountP2 >= 7);
    } elseif (isset($bimbinganCountP1)) {
        // Jika hanya ada satu pembimbing
        $syaratJumlahBimbinganTerpenuhi = ($bimbinganCountP1 >= 7);
    }
@endphp

{{-- Panel Pendaftaran Sidang (Hanya Muncul Jika Syarat Terpenuhi) --}}
@if ($syaratJumlahBimbinganTerpenuhi)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-patch-check-fill me-2 text-success"></i>
                Pendaftaran Sidang Akhir
            </h5>

            @if (isset($pendaftaranTerbaru) && $pendaftaranTerbaru)
                {{-- LOGIKA 1: Mahasiswa SUDAH pernah mendaftar. Tampilkan status pendaftaran. --}}

                @if ($pendaftaranTerbaru->status_verifikasi === 'disetujui')
                    <div class="alert alert-success">
                        <h6 class="alert-heading fw-bold">Pendaftaran Disetujui!</h6>
                        <p class="mb-0">Selamat! Pendaftaran sidang Anda telah disetujui. Silakan tunggu informasi jadwal sidang dari program studi.</p>
                    </div>

                @elseif ($pendaftaranTerbaru->status_verifikasi === 'berkas_tidak_lengkap')
                    <div class="alert alert-danger">
                        <h6 class="alert-heading fw-bold">Pendaftaran Ditolak</h6>
                        <p>Pendaftaran sidang Anda ditolak. Silakan perbaiki berkas sesuai catatan dari dosen dan lakukan pendaftaran ulang.</p>
                        <hr>
                        <a href="{{-- Ganti dengan route ke form daftar ulang --}}" class="btn btn-danger w-100">
                            <i class="bi bi-arrow-repeat me-1"></i> Daftar Ulang Sidang
                        </a>
                    </div>

                @elseif ($pendaftaranTerbaru->status_verifikasi === 'menunggu_verifikasi')
                    <p>Pendaftaran Anda sedang dalam proses verifikasi oleh dosen pembimbing. Mohon tunggu.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Status Pembimbing 1</span>
                            @php
                                $statusP1 = match ($pendaftaranTerbaru->status_pembimbing_1) {
                                    'menunggu' => ['text' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                                    'disetujui' => ['text' => 'Disetujui', 'class' => 'bg-success'],
                                    'ditolak' => ['text' => 'Ditolak', 'class' => 'bg-danger'],
                                };
                            @endphp
                            <span class="badge {{ $statusP1['class'] }} rounded-pill">{{ $statusP1['text'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Status Pembimbing 2</span>
                             @php
                                $statusP2 = match ($pendaftaranTerbaru->status_pembimbing_2) {
                                    'menunggu' => ['text' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                                    'disetujui' => ['text' => 'Disetujui', 'class' => 'bg-success'],
                                    'ditolak' => ['text' => 'Ditolak', 'class' => 'bg-danger'],
                                };
                            @endphp
                            <span class="badge {{ $statusP2['class'] }} rounded-pill">{{ $statusP2['text'] }}</span>
                        </li>
                    </ul>
                @endif

            @else
                {{-- LOGIKA 2: Mahasiswa BELUM mendaftar, tapi sudah memenuhi syarat. --}}
                <div class="alert alert-info text-center">
                    <p class="mb-2">Anda telah memenuhi syarat minimal bimbingan.</p>
                    <h6 class="fw-bold">Anda sudah bisa mendaftar Sidang Akhir.</h6>
                </div>
                <div class="d-grid">
                    <a href="{{ route('mahasiswa.sidang.daftar-akhir') }}" class="btn btn-success w-100">
                        <i class="bi bi-send-check me-1"></i> Lanjutkan ke Pendaftaran Sidang
                    </a>
                </div>
            @endif

        </div>
    </div>
@endif
