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
</div>

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
                            <i class="bi bi-hourglass-split text-white fs-3"></i>
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
                        <div class="spinner-border text-warning mb-3"
                            style="width: 3rem; height: 3rem; animation-duration: 5s;">
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
            <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm" data-bs-toggle="modal"
                data-bs-target="#uploadFileModal{{ $tugasAkhir->id }}">
                <i class="bi bi-plus-circle me-2"> Ajukan Jadwal Baru</i>
            </button>
        </div>
    </div>
@endforelse


{{-- Syarat Panel Pendaftaran Sidang --}}
@php
    // Calculate if bimbingan requirements are met
    $syaratJumlahBimbinganTerpenuhi = false;
    if (isset($bimbinganCountP1) && isset($bimbinganCountP2)) {
        $syaratJumlahBimbinganTerpenuhi = $bimbinganCountP1 >= 7 && $bimbinganCountP2 >= 7;
    } elseif (isset($bimbinganCountP1)) {
        $syaratJumlahBimbinganTerpenuhi = $bimbinganCountP1 >= 7;
    }

    // Status badge configuration
    $statusConfig = [
        'menunggu' => ['text' => 'Menunggu', 'class' => 'bg-warning text-dark'],
        'disetujui' => ['text' => 'Disetujui', 'class' => 'bg-success'],
        'ditolak' => ['text' => 'Ditolak', 'class' => 'bg-danger'],
        'berkas_tidak_lengkap' => ['text' => 'Berkas Tidak Lengkap', 'class' => 'bg-danger'],
    ];
@endphp

{{-- Panel Pendaftaran Sidang --}}
@if ($syaratJumlahBimbinganTerpenuhi)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-patch-check-fill me-2 text-success"></i>
                Pendaftaran Sidang Akhir
            </h5>

            {{-- Case 1: Already registered --}}
            @if (isset($pendaftaranTerbaru) && $pendaftaranTerbaru)
                @switch($pendaftaranTerbaru->status_verifikasi)
                    @case('disetujui')
                        <div class="alert alert-success">
                            <h6 class="alert-heading fw-bold">Pendaftaran Disetujui!</h6>
                            <p class="mb-0">Selamat! Pendaftaran sidang Anda telah disetujui. Silakan tunggu informasi
                                jadwal sidang dari program studi.</p>
                        </div>
                    @break

                    @case('berkas_tidak_lengkap')
                        <div class="alert alert-danger">
                            <h6 class="alert-heading fw-bold">Perbaikan Berkas Diperlukan</h6>
                            <p class="mb-3">Pendaftaran sidang Anda memerlukan perbaikan berkas:</p>
                            @if ($pendaftaranTerbaru->catatan)
                                <div class="bg-light p-3 mb-3 rounded">
                                    <strong>Catatan:</strong> {{ $pendaftaranTerbaru->catatan }}
                                </div>
                            @endif
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#sidangModal">
                                <i class="bi bi-arrow-repeat me-1"></i> Perbaiki & Daftar Ulang
                            </button>
                        </div>
                    @break

                    @case('menunggu_verifikasi')
                        <div class="alert alert-info">
                            <h6 class="alert-heading fw-bold">Pendaftaran Sedang Diproses</h6>
                            <p class="mb-3">Pendaftaran Anda sedang dalam proses verifikasi oleh dosen pembimbing.</p>

                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                    <span>Pembimbing 1</span>
                                    @php $status = $statusConfig[$pendaftaranTerbaru->status_pembimbing_1] ?? $statusConfig['menunggu']; @endphp
                                    <span class="badge {{ $status['class'] }} rounded-pill">{{ $status['text'] }}</span>
                                </li>
                                @if ($pendaftaranTerbaru->pembimbing2_id)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                        <span>Pembimbing 2</span>
                                        @php $status = $statusConfig[$pendaftaranTerbaru->status_pembimbing_2] ?? $statusConfig['menunggu']; @endphp
                                        <span class="badge {{ $status['class'] }} rounded-pill">{{ $status['text'] }}</span>
                                    </li>
                                @endif
                            </ul>

                            @if ($pendaftaranTerbaru->created_at)
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Didaftarkan pada: {{ $pendaftaranTerbaru->created_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    @break

                    @default
                        <div class="alert alert-secondary">
                            <h6 class="alert-heading fw-bold">Status Pendaftaran</h6>
                            <p class="mb-0">Status pendaftaran Anda: {{ $pendaftaranTerbaru->status_verifikasi }}</p>
                        </div>
                @endswitch
            @else
                {{-- Case 2: Eligible but not registered yet --}}
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Anda Memenuhi Syarat Sidang</h6>
                            <p class="mb-0">Anda telah memenuhi syarat minimal bimbingan untuk mendaftar sidang
                                akhir.</p>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success w-100 py-2" data-bs-toggle="modal"
                    data-bs-target="#sidangModal">
                    <i class="bi bi-send-check me-1"></i> Ajukan Pendaftaran Sidang
                </button>
            @endif
        </div>
    </div>

    {{-- Modal Form Pendaftaran --}}
    <div class="modal fade" id="sidangModal" tabindex="-1" aria-labelledby="sidangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="sidangModalLabel">Form Pendaftaran Sidang Tugas Akhir</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form id="formSidang" action="{{ route('mahasiswa.sidang.store-akhir') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        @if (isset($pendaftaranTerbaru) && $pendaftaranTerbaru)
                            <input type="hidden" name="is_edit" value="1">
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control"
                                    value="{{ $mahasiswa->user->name ?? '-' }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" value="{{ $mahasiswa->nim ?? '-' }}"
                                    readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Judul Tugas Akhir <span class="text-danger">*</span></label>
                                <input type="text" name="judul_ta" class="form-control"
                                    value="{{ old('judul_ta', $pendaftaranTerbaru->judul_ta ?? '') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dosen Pembimbing 1</label>
                                <input type="text" class="form-control"
                                    value="{{ $mahasiswa->tugasAkhir?->pembimbingSatu?->dosen?->user?->name ?? '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dosen Pembimbing 2</label>
                                <input type="text" class="form-control"
                                    value="{{ $mahasiswa->tugasAkhir?->pembimbingDua?->dosen?->user?->name ?? '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jumlah Bimbingan <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_bimbingan" class="form-control" min="7"
                                    value="{{ old('jumlah_bimbingan', $bimbinganCountP1 ?? 0) }}" required>
                                <small class="text-muted">Minimal 7 kali bimbingan</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload File Tugas Akhir <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="file_ta" class="form-control" accept=".pdf,.doc,.docx"
                                    required>
                                <small class="text-muted">Format: PDF/DOC/DOCX (Max: 5MB)</small>

                                @if (isset($pendaftaranTerbaru) && $pendaftaranTerbaru?->file_ta)
                                    <div class="mt-2">
                                        <span class="badge bg-info">File Terupload:
                                            {{ basename($pendaftaranTerbaru->file_ta) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 pt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Tutup
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
