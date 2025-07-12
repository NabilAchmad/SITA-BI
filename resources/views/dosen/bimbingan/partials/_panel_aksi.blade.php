@php
    // Menyiapkan variabel untuk memeriksa peran dosen yang sedang login.
    // Ini memastikan tombol aksi hanya muncul untuk dosen yang berwenang.
    $isP1 = $pembimbing1 && Auth::user()->dosen->id === $pembimbing1->dosen_id;
    $isP2 = $pembimbing2 && Auth::user()->dosen->id === $pembimbing2->dosen_id;
@endphp

{{-- Panel Status & Progress --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            {{-- Menampilkan progress bimbingan per dosen --}}
            @if ($pembimbing1)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check-fill me-2"></i>P1:
                        {{ Str::limit($pembimbing1->dosen->user->name, 50) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP1 }}
                        / 7</span>
                </li>
            @endif
            @if ($pembimbing2)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check me-2"></i>P2:
                        {{ Str::limit($pembimbing2->dosen->user->name, 50) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP2 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP2 }}
                        / 7</span>
                </li>
            @endif

            {{-- Badge kelayakan sidang yang dinamis --}}
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span>Kelayakan Sidang</span>
                @php
                    // Logika ini sudah benar untuk menampilkan status persetujuan
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
@php
    $jadwalAktif = $tugasAkhir->bimbinganTa()->where('status_bimbingan', 'dijadwalkan')->latest()->first();
@endphp
@if ($jadwalAktif)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event me-2 text-success"></i>Jadwal Berikutnya
            </h5>
            <p class="mb-1"><strong>Tanggal:</strong> {{ $jadwalAktif->tanggal_bimbingan->format('d F Y') }}</p>
            <p class="mb-1"><strong>Jam:</strong>
                {{ \Carbon\Carbon::parse($jadwalAktif->jam_bimbingan)->format('H:i') }} WIB</p>
            <p><strong>Oleh:</strong> {{ $jadwalAktif->dosen->user->name }}</p>
            <div class="d-flex gap-2 mt-3">
                {{-- Tombol "Tandai Selesai" bisa ditekan oleh kedua dosen --}}
                <form action="{{ route('dosen.jadwal.selesai', $jadwalAktif->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill">Tandai Selesai</button>
                </form>
                {{-- Tombol "Batalkan" hanya bisa ditekan oleh dosen yang membuat jadwal --}}
                @if (Auth::user()->dosen->id === $jadwalAktif->dosen_id)
                    <form action="{{ route('dosen.jadwal.cancel', $jadwalAktif->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"
                            onclick="return confirm('Anda yakin ingin membatalkan sesi bimbingan ini?')">Batalkan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Panel Aksi Utama --}}
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-ui-checks-grid me-2 text-success"></i>Aksi Utama</h5>
        <div class="d-grid gap-2">
            {{-- Tombol Jadwal hanya aktif untuk Pembimbing 1 --}}
            @if ($isP1)
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBuatJadwal">
                    <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Bimbingan Baru
                </button>
            @else
                <button type="button" class="btn btn-success" disabled
                    title="Hanya Pembimbing 1 yang dapat menjadwalkan sesi baru.">
                    <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Bimbingan Baru
                </button>
            @endif

            {{-- Logika Persetujuan Sidang yang Dinamis --}}
            @php
                $syaratJumlahBimbinganTerpenuhi = $bimbinganCountP1 >= 7 && $bimbinganCountP2 >= 7;
            @endphp

            @if ($syaratJumlahBimbinganTerpenuhi && $tugasAkhir->status !== 'layak_sidang')
                <hr>
                <h6 class="text-muted text-center small my-1">Persetujuan Sidang</h6>

                {{-- ✅ PERBAIKAN: Logika persetujuan disederhanakan dan dibuat lebih aman --}}
                @if ($isP1 && $tugasAkhir->status === 'menunggu_acc_p1')
                    <form action="{{ route('dosen.sidang.setujui', $tugasAkhir->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Beri Persetujuan Sidang</button>
                    </form>
                @elseif ($isP2 && $tugasAkhir->status === 'menunggu_acc_p2')
                    <form action="{{ route('dosen.sidang.setujui', $tugasAkhir->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Beri Persetujuan Sidang</button>
                    </form>
                @else
                    <button class="btn btn-outline-secondary w-100" disabled>Menunggu Aksi Lain</button>
                @endif
            @endif
        </div>
    </div>
</div>
