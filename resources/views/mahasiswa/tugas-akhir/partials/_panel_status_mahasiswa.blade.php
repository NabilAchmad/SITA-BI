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
        </ul>
    </div>
</div>
{{-- <div class="card shadow-sm border-0 rounded-4 mb-4">
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
                <span>Kelayakan Sidang</span>
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
@php
    $jadwalAktif = [];

    // Ambil jadwal terbaru untuk Pembimbing 1 jika ada
    if ($pembimbing1) {
        $jadwalP1 = $tugasAkhir->bimbinganTa()->where('peran', 'pembimbing1')->latest('sesi_ke')->first();
        // Tampilkan hanya jika statusnya aktif (diajukan atau dijadwalkan)
        if ($jadwalP1 && in_array($jadwalP1->status_bimbingan, ['diajukan', 'dijadwalkan'])) {
            $jadwalAktif[] = $jadwalP1;
        }
    }

    // Ambil jadwal terbaru untuk Pembimbing 2 jika ada
    if ($pembimbing2) {
        $jadwalP2 = $tugasAkhir->bimbinganTa()->where('peran', 'pembimbing2')->latest('sesi_ke')->first();
        // Tampilkan hanya jika statusnya aktif (diajukan atau dijadwalkan)
        if ($jadwalP2 && in_array($jadwalP2->status_bimbingan, ['diajukan', 'dijadwalkan'])) {
            $jadwalAktif[] = $jadwalP2;
        }
    }
@endphp

@forelse ($jadwalAktif as $jadwal)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3">
                <i
                    class="bi bi-calendar-event me-2
                    @if ($jadwal->status_bimbingan == 'dijadwalkan') text-success
                    @elseif($jadwal->status_bimbingan == 'diajukan') text-warning @endif"></i>
                Jadwal Aktif - {{ $jadwal->peran == 'pembimbing1' ? 'Pembimbing 1' : 'Pembimbing 2' }}
            </h5>

            <p class="mb-1"><strong>Status:</strong>
                <span
                    class="text-capitalize badge rounded-pill
                    @if ($jadwal->status_bimbingan == 'dijadwalkan') bg-success
                    @elseif($jadwal->status_bimbingan == 'diajukan') bg-warning text-dark @endif">
                    {{ $jadwal->status_bimbingan }}
                </span>
            </p>
            <p class="mb-1"><strong>Dosen:</strong> {{ $jadwal->dosen->user->name ?? '-' }}</p>

            @if ($jadwal->tanggal_bimbingan)
                <p class="mb-1"><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_bimbingan)->format('d F Y') }}</p>
            @else
                <p class="mb-1"><strong>Tanggal:</strong> <span class="text-muted">Menunggu konfirmasi</span></p>
            @endif

            @if ($jadwal->jam_bimbingan)
                <p class="mb-1"><strong>Jam:</strong>
                    {{ \Carbon\Carbon::parse($jadwal->jam_bimbingan)->format('H:i') }} WIB</p>
            @else
                <p class="mb-1"><strong>Jam:</strong> <span class="text-muted">Menunggu konfirmasi</span></p>
            @endif

            {{-- Opsi Aksi Berdasarkan Status --}}
            @if ($jadwal->status_bimbingan == 'dijadwalkan')
                <a href="#" class="btn btn-sm btn-outline-warning rounded-pill w-100 mt-3">Ajukan Perubahan
                    Jadwal</a>
            @endif
        </div>
    </div>
@empty
    <div class="alert alert-info">
        Belum ada jadwal bimbingan aktif yang diajukan atau dijadwalkan.
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
