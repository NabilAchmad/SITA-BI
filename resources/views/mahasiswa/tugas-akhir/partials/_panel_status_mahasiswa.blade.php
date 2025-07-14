{{-- Panel Status & Progress --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            @if ($pembimbing1 && $pembimbing1->dosen && $pembimbing1->dosen->user)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check-fill me-2"></i>P1:
                        {{ Str::limit($pembimbing1->dosen->user->name, 15) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP1 }}
                        / 7</span>
                </li>
            @endif
            @if ($pembimbing2 && $pembimbing2->dosen && $pembimbing2->dosen->user)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check me-2"></i>P2:
                        {{ Str::limit($pembimbing2->dosen->user->name, 15) }}</span>
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
</div>

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
            <p class="mb-1"><strong>Dosen:</strong> {{ $jadwal->dosen->user->name }}</p>

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
