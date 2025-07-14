@php
    // =================================================================
    // BLOK LOGIKA YANG SUDAH DIPERBAIKI
    // =================================================================
    $dosenLogin = Auth::user()->dosen;

    // Perbaikan 1: Menggunakan '->id' karena $pembimbing1 adalah objek Dosen
    $isP1 = $pembimbing1 && $dosenLogin && $dosenLogin->id === $pembimbing1->id;
    $isP2 = $pembimbing2 && $dosenLogin && $dosenLogin->id === $pembimbing2->id;

    // Logika ini sudah benar, menggunakan ->id untuk perbandingan
    $sesiP1 = $pembimbing1 ? $sesiAktif->where('dosen_id', $pembimbing1->id)->first() : null;
    $sesiP2 = $pembimbing2 ? $sesiAktif->where('dosen_id', $pembimbing2->id)->first() : null;

    // Syarat jumlah bimbingan
    $syaratJumlahBimbinganTerpenuhi = $bimbinganCountP1 >= 7 && ($pembimbing2 ? $bimbinganCountP2 >= 7 : true);
@endphp

{{-- Panel Status & Progress --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2 text-info"></i>Status & Progress</h5>
        <ul class="list-group list-group-flush">
            @if ($pembimbing1)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check-fill me-2"></i>Pembimbing 1:
                        {{-- Pemanggilan aman menggunakan optional chaining --}}
                        {{ Str::limit($pembimbing1?->dosen->user?->name, 50) }}</span>
                    <span class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">
                        {{ $bimbinganCountP1 }} / 7
                    </span>
                </li>
            @endif
            @if ($pembimbing2)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><i class="bi bi-person-check me-2"></i>Pembimbing 2:
                        {{-- Pemanggilan aman menggunakan optional chaining --}}
                        {{ Str::limit($pembimbing2?->dosen->user?->name, 50) }}</span>
                    <span class="badge {{ $bimbinganCountP2 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill">
                        {{ $bimbinganCountP2 }} / 7
                    </span>
                </li>
            @endif
        </ul>
    </div>
</div>

{{-- Panel Status Bimbingan --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-primary bg-opacity-10 py-3 border-0">
        <h5 class="mb-0 fw-bold text-white">
            <i class="bi bi-calendar2-week me-2 text-white"></i> Status Bimbingan Tugas Akhir
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach ([['pembimbing' => $pembimbing1, 'sesi' => $sesiP1, 'isDosen' => $isP1], ['pembimbing' => $pembimbing2, 'sesi' => $sesiP2, 'isDosen' => $isP2]] as $item)
                @if ($item['pembimbing'])
                    <div class="list-group-item py-4 px-4 bg-light border-bottom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary bg-opacity-25 text-white mb-1 text-capitalize">
                                    Pembimbing {{ $loop->iteration }}:
                                    {{ $item['pembimbing']->dosen->user->name ?? 'Tidak Diketahui' }}
                                </span>

                                {{-- Pemanggilan aman menggunakan optional chaining --}}
                                <h6 class="mb-0 fw-semibold">{{ $item['pembimbing']?->user?->name }}</h6>
                            </div>
                            @if ($item['sesi'])
                                <span
                                    class="badge rounded-pill fs-6 text-capitalize
                                    @if ($item['sesi']->status_bimbingan === 'dijadwalkan') bg-success
                                    @elseif($item['sesi']->status_bimbingan === 'diajukan') bg-warning text-dark
                                    @elseif($item['sesi']->status_bimbingan === 'selesai') bg-info @endif">
                                    {{ str_replace('_', ' ', $item['sesi']->status_bimbingan) }}
                                </span>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            @if ($item['sesi'] && $item['sesi']->status_bimbingan === 'dijadwalkan')
                                <div class="alert alert-success d-flex align-items-start gap-3 py-2 px-3 h-100">
                                    <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold">Jadwal Telah Dikonfirmasi</div>
                                        <div class="small">
                                            {{ optional($item['sesi']->tanggal_bimbingan)->translatedFormat('l, d F Y') }}
                                            |
                                            {{ \Carbon\Carbon::parse($item['sesi']->jam_bimbingan)->format('H:i') }}
                                            WIB
                                        </div>
                                    </div>
                                </div>
                            @elseif($item['sesi'] && $item['sesi']->status_bimbingan === 'diajukan')
                                <div class="alert alert-warning d-flex align-items-start gap-3 py-2 px-3 h-100">
                                    <i class="bi bi-hourglass-split fs-5 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold">Menunggu Konfirmasi Jadwal</div>
                                        <div class="small">Pengajuan bimbingan Anda sedang ditinjau oleh dosen.</div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-secondary d-flex align-items-start gap-3 py-2 px-3 h-100">
                                    <i class="bi bi-info-circle fs-5 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold">Belum Ada Sesi Aktif</div>
                                        <div class="small">Beri arahan kepada mahasiswa untuk upload file tugas akhir.</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi untuk Dosen Pembimbing --}}
                        <div class="mt-3" style="min-height: 5px;">
                            {{-- Perbaikan 1 memastikan $item['isDosen'] bernilai benar --}}
                            @if ($item['isDosen'])
                                @if ($item['sesi'] && $item['sesi']->status_bimbingan === 'dijadwalkan')
                                    <div class="d-flex gap-2">
                                        <form method="POST"
                                            action="{{ route('dosen.jadwal.selesai', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $item['sesi']]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                            </button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('dosen.jadwal.cancel', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $item['sesi']]) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                                                <i class="bi bi-x-circle me-1"></i> Batalkan
                                            </button>
                                        </form>
                                    </div>
                                @elseif ($item['sesi'] && $item['sesi']->status_bimbingan === 'diajukan')
                                    {{-- Di dalam @if ($item['sesi'] && $item['sesi']->status_bimbingan === 'diajukan') --}}

                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3"
                                        data-bs-toggle="modal" {{-- ✅ PERBAIKAN 1: Targetkan ID modal yang statis --}} data-bs-target="#modalBuatJadwal"
                                        {{-- ✅ PERBAIKAN 2: Simpan URL unik di data-action --}}
                                        data-action="{{ route('dosen.jadwal.store', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $item['sesi']]) }}">

                                        <i class="bi bi-calendar-plus me-1"></i> Atur Jadwal
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>


{{-- Panel Persetujuan Sidang --}}
@if ($syaratJumlahBimbinganTerpenuhi && $tugasAkhir->status !== 'layak_sidang')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-patch-check-fill me-2 text-primary"></i>Persetujuan
                Sidang</h5>
            <div class="d-grid gap-2">
                @if (($isP1 && $tugasAkhir->status === 'menunggu_acc_p1') || ($isP2 && $tugasAkhir->status === 'menunggu_acc_p2'))
                    <form action="{{ route('dosen.sidang.setujui', $tugasAkhir->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Beri Persetujuan Sidang</button>
                    </form>
                @else
                    <button class="btn btn-outline-secondary w-100" disabled>Menunggu Aksi Lain</button>
                @endif
            </div>
        </div>
    </div>
@endif
