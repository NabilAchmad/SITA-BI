{{-- Panel 1: Status & Progress Bimbingan --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
            <i class="bi bi-speedometer2 me-2 text-info"></i>
            Status & Progress Bimbingan
        </h5>

        <div class="row g-3">
            @if ($pembimbing1)
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check-fill me-3 text-primary fs-5"></i>
                            <div>
                                <div class="fw-semibold text-dark">Pembimbing 1</div>
                                <div class="text-muted small">{{ Str::limit($pembimbing1?->dosen?->user?->name, 50) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span
                                class="badge {{ $bimbinganCountP1 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill px-3 py-2 fs-6">
                                {{ $bimbinganCountP1 }} / 7
                            </span>
                            <div class="small text-muted mt-1">
                                {{ $bimbinganCountP1 >= 7 ? 'Selesai' : 'Dalam Progress' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($pembimbing2)
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check me-3 text-primary fs-5"></i>
                            <div>
                                <div class="fw-semibold text-dark">Pembimbing 2</div>
                                <div class="text-muted small">{{ Str::limit($pembimbing2?->dosen?->user?->name, 50) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span
                                class="badge {{ $bimbinganCountP2 >= 7 ? 'bg-success' : 'bg-primary' }} rounded-pill px-3 py-2 fs-6">
                                {{ $bimbinganCountP2 }} / 7
                            </span>
                            <div class="small text-muted mt-1">
                                {{ $bimbinganCountP2 >= 7 ? 'Selesai' : 'Dalam Progress' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Panel 2: Status & Aksi Bimbingan Aktif --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-primary bg-opacity-10 py-3 border-0">
        <h5 class="mb-0 fw-bold text-white d-flex align-items-center">
            <i class="bi bi-calendar2-week me-2 text-white"></i>
            Status Bimbingan Aktif
        </h5>
    </div>

    <div class="card-body p-0">
        {{-- Loop untuk Pembimbing 1 dan 2 --}}
        @foreach ([['pembimbing' => $pembimbing1, 'isDosen' => $isDosenP1], ['pembimbing' => $pembimbing2, 'isDosen' => $isDosenP2]] as $item)
            @if ($item['pembimbing'])
                @php
                    // Mengambil sesi aktif untuk pembimbing saat ini dari koleksi
                    $sesi = $sesiAktif->where('dosen_id', $item['pembimbing']->dosen_id)->first();
                @endphp

                <div class="border-bottom {{ $loop->last ? 'border-0' : '' }}">
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-person-badge text-white"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">
                                {{ $loop->iteration === 1 ? 'Pembimbing 1' : 'Pembimbing 2' }}
                            </h6>
                        </div>

                        @if ($sesi)
                            {{-- Tampilkan status sesi bimbingan --}}
                            @if ($sesi->status_bimbingan === 'dijadwalkan')
                                <div class="alert alert-success d-flex align-items-start border-0 rounded-3 mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-check-circle-fill text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-success mb-1">Jadwal Telah Dikonfirmasi</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ optional($sesi->tanggal_bimbingan)->translatedFormat('l, d M Y') }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($sesi->jam_bimbingan)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>
                            @elseif($sesi->status_bimbingan === 'diajukan')
                                <div class="alert alert-warning d-flex align-items-start border-0 rounded-3 mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-hourglass-split text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-warning mb-1">Menunggu Konfirmasi Jadwal</div>
                                        <div class="text-muted small">Mahasiswa telah mengajukan sesi bimbingan.</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Tampilkan tombol aksi HANYA untuk dosen yang bersangkutan --}}
                            @if ($item['isDosen'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if ($sesi->status_bimbingan === 'diajukan')
                                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#modalBuatJadwal"
                                            data-action="{{ route('dosen.jadwal.store', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $sesi]) }}">
                                            <i class="bi bi-calendar-plus me-1"></i>
                                            Atur Jadwal
                                        </button>
                                    @elseif ($sesi->status_bimbingan === 'dijadwalkan')
                                        <form method="POST" class="d-inline"
                                            action="{{ route('dosen.jadwal.selesai', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $sesi]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Tandai Selesai
                                            </button>
                                        </form>
                                        <form method="POST" class="d-inline"
                                            action="{{ route('dosen.jadwal.cancel', ['tugasAkhir' => $tugasAkhir, 'bimbingan' => $sesi]) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @else
                            {{-- Tampilan jika tidak ada sesi aktif --}}
                            <div class="alert alert-secondary text-center border-0 rounded-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Belum ada sesi bimbingan yang aktif.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

{{-- Panel 3: Verifikasi Pendaftaran Sidang --}}
@if ($apakahSyaratBimbinganTerpenuhi)
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                <i class="bi bi-file-earmark-check-fill me-2 text-primary"></i>
                Verifikasi Pendaftaran Sidang
            </h5>

            <div class="d-grid gap-2">
                @if (isset($pendaftaranTerbaru))
                    @php
                        $statusDosenIni = $isDosenP1
                            ? $pendaftaranTerbaru->status_pembimbing_1
                            : ($isDosenP2
                                ? $pendaftaranTerbaru->status_pembimbing_2
                                : null);
                    @endphp

                    @if ($statusDosenIni === 'menunggu')
                        <a href="{{ route('dosen.verifikasi-sidang.show', $pendaftaranTerbaru->id) }}"
                            class="btn btn-primary btn-lg rounded-pill">
                            <i class="bi bi-search me-2"></i>
                            Lihat Berkas & Verifikasi
                        </a>
                    @elseif ($statusDosenIni === 'disetujui')
                        <div class="alert alert-success text-center border-0 rounded-3 py-3 mb-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-2"
                                style="width: fit-content;">
                                <i class="bi bi-check-circle-fill text-white fs-4"></i>
                            </div>
                            <div class="fw-semibold">Anda telah menyetujui pendaftaran sidang</div>
                        </div>
                    @elseif ($statusDosenIni === 'ditolak')
                        <div class="alert alert-danger text-center border-0 rounded-3 py-3 mb-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-2"
                                style="width: fit-content;">
                                <i class="bi bi-x-circle-fill text-white fs-4"></i>
                            </div>
                            <div class="fw-semibold">Anda telah menolak pendaftaran sidang</div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info text-center border-0 rounded-3 py-3 mb-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: fit-content;">
                            <i class="bi bi-hourglass-split text-info fs-4"></i>
                        </div>
                        <div class="fw-semibold">Menunggu mahasiswa mendaftar sidang</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
