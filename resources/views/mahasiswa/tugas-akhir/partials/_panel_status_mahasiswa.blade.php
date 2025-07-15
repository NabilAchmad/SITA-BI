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

{{-- Panel Pendaftaran Sidang --}}
@if ($isEligibleForRegistration)
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-4" id="sidangModalLabel">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Form Pendaftaran Sidang Tugas Akhir
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <h5 class="alert-heading mb-0">Terdapat Kesalahan!</h5>
                            </div>
                            <p class="mb-2">Silakan periksa kembali isian Anda. Semua berkas wajib diunggah.</p>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li class="mb-1">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="formPendaftaranSidang" action="{{ route('mahasiswa.sidang.store-akhir') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Bagian Data Mahasiswa (Read-only) --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-person-badge me-2 text-primary"></i>
                                    Data Mahasiswa
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-person me-1"></i>
                                            Nama Mahasiswa
                                        </label>
                                        <input type="text" class="form-control bg-light border-0"
                                            value="{{ $mahasiswa?->user?->name ?? 'Tidak ada data' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-credit-card me-1"></i>
                                            NIM
                                        </label>
                                        <input type="text" class="form-control bg-light border-0"
                                            value="{{ $mahasiswa?->nim ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-book me-1"></i>
                                            Judul Tugas Akhir
                                        </label>
                                        <textarea class="form-control bg-light border-0" rows="3" readonly>{{ $tugasAkhir?->judul ?? 'Judul TA belum ditetapkan' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            Dosen Pembimbing 1
                                        </label>
                                        <input type="text" class="form-control bg-light border-0"
                                            value="{{ $pembimbing1?->dosen?->user?->name ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            Dosen Pembimbing 2
                                        </label>
                                        <input type="text" class="form-control bg-light border-0"
                                            value="{{ $pembimbing2?->dosen?->user?->name ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bagian Upload Berkas (Checklist Digital) --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-cloud-upload me-2 text-primary"></i>
                                    Kelengkapan Berkas Pendaftaran
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                        <div>
                                            <p class="mb-1">
                                                Silakan unggah semua dokumen yang disyaratkan sesuai dengan
                                                <a href="#" target="_blank" class="text-decoration-none">
                                                    <i class="bi bi-file-pdf me-1"></i>Buku Panduan TA
                                                </a>.
                                            </p>
                                            <small class="text-muted">
                                                Semua file harus dalam format yang ditentukan dan ukuran sesuai
                                                ketentuan.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div
                                            class="border border-2 border-dashed rounded-3 p-4 bg-light bg-opacity-50">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-file-earmark-text text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <label for="file_naskah_ta" class="form-label fw-bold mb-0">
                                                        1. Naskah Final Tugas Akhir
                                                        <span class="badge bg-danger ms-2">Wajib</span>
                                                    </label>
                                                    <small class="d-block text-muted">Format: PDF, DOC, DOCX •
                                                        Maksimal: 10MB</small>
                                                </div>
                                            </div>
                                            <input type="file" name="file_naskah_ta" id="file_naskah_ta"
                                                class="form-control form-control-lg border-2" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="border border-2 border-dashed rounded-3 p-4 bg-light bg-opacity-50">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-award text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <label for="file_toeic" class="form-label fw-bold mb-0">
                                                        2. Sertifikat TOEIC
                                                        <span class="badge bg-danger ms-2">Wajib</span>
                                                    </label>
                                                    <small class="d-block text-muted">Skor minimal 500 • Format: PDF •
                                                        Maksimal: 2MB</small>
                                                </div>
                                            </div>
                                            <input type="file" name="file_toeic" id="file_toeic"
                                                class="form-control form-control-lg border-2" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="border border-2 border-dashed rounded-3 p-4 bg-light bg-opacity-50">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-journal-text text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    @if ($mahasiswa?->prodi === 'd3')
                                                        <label for="file_rapor" class="form-label fw-bold mb-0">
                                                            3. Scan Rapor Semester 1-5
                                                            <span class="badge bg-danger ms-2">Wajib</span>
                                                        </label>
                                                    @elseif($mahasiswa?->prodi === 'd4')
                                                        <label for="file_rapor" class="form-label fw-bold mb-0">
                                                            3. Scan Rapor Semester 1-7
                                                            <span class="badge bg-danger ms-2">Wajib</span>
                                                        </label>
                                                    @endif
                                                    <small class="d-block text-muted">Jadikan dalam satu file PDF •
                                                        Maksimal: 5MB</small>
                                                </div>
                                            </div>
                                            <input type="file" name="file_rapor" id="file_rapor"
                                                class="form-control form-control-lg border-2" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="border border-2 border-dashed rounded-3 p-4 bg-light bg-opacity-50">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-patch-check text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <label for="file_ijazah_slta" class="form-label fw-bold mb-0">
                                                        4. Scan Ijazah SLTA
                                                        <span class="badge bg-danger ms-2">Wajib</span>
                                                    </label>
                                                    <small class="d-block text-muted">Format: PDF • Maksimal:
                                                        2MB</small>
                                                </div>
                                            </div>
                                            <input type="file" name="file_ijazah_slta" id="file_ijazah_slta"
                                                class="form-control form-control-lg border-2" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="border border-2 border-dashed rounded-3 p-4 bg-light bg-opacity-50">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-shield-check text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <label for="file_bebas_jurusan" class="form-label fw-bold mb-0">
                                                        5. Surat Keterangan Bebas Jurusan
                                                        <span class="badge bg-danger ms-2">Wajib</span>
                                                    </label>
                                                    <small class="d-block text-muted">Format: PDF • Maksimal:
                                                        2MB</small>
                                                </div>
                                            </div>
                                            <input type="file" name="file_bebas_jurusan" id="file_bebas_jurusan"
                                                class="form-control form-control-lg border-2" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <div class="d-flex gap-2 w-100 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="submit" form="formPendaftaranSidang" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i> Kirim Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
