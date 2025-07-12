{{-- Panel Status & Progress --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            @if ($pembimbing1)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check-fill me-2"></i>P1:
                        {{ Str::limit($pembimbing1->dosen->user->name, 15) }}</span>
                    <span
                        class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ $bimbinganCountP1 }}
                        / 7</span>
                </li>
            @endif
            @if ($pembimbing2)
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
    $jadwalBimbingan = $tugasAkhir->bimbinganTa()->latest()->get();
@endphp

@forelse ($jadwalBimbingan as $jadwal)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3">
                <i
                    class="bi bi-calendar-event me-2 
                    @if ($jadwal->status_bimbingan == 'dijadwalkan') text-success
                    @elseif($jadwal->status_bimbingan == 'diajukan') text-warning @endif"></i>
                Jadwal Bimbingan {{ $loop->iteration }} -
                <span class="text-capitalize">{{ $jadwal->status_bimbingan }}</span>
            </h5>

            <p class="mb-1"><strong>Dosen:</strong> {{ $jadwal->dosen->user->name }} ({{ $jadwal->peran }})</p>

            @if ($jadwal->tanggal_bimbingan)
                <p class="mb-1"><strong>Tanggal:</strong> {{ $jadwal->tanggal_bimbingan->format('d F Y') }}</p>
            @else
                <p class="mb-1"><strong>Tanggal:</strong> Belum dijadwalkan</p>
            @endif

            @if ($jadwal->jam_bimbingan)
                <p class="mb-1"><strong>Jam:</strong>
                    {{ \Carbon\Carbon::parse($jadwal->jam_bimbingan)->format('H:i') }} WIB</p>
            @else
                <p class="mb-1"><strong>Jam:</strong> Belum ditentukan</p>
            @endif

            @if ($jadwal->status_bimbingan == 'dijadwalkan')
                <a href="#" class="btn btn-sm btn-outline-warning rounded-pill w-100 mt-2">Ajukan Perubahan
                    Jadwal</a>
            @endif
        </div>
    </div>
@empty
    <div class="alert alert-info">
        Belum ada jadwal bimbingan yang tersedia.
    </div>
@endforelse
