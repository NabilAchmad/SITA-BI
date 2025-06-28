@if ($jadwal->historyPerubahan && $jadwal->historyPerubahan->count())
    <div class="mt-4">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-clock-history fs-4 text-warning me-2"></i>
            <h5 class="fw-bold text-warning mb-0">Riwayat Perubahan Jadwal</h5>
            <span class="badge bg-warning text-dark ms-2">
                {{ $jadwal->historyPerubahan->count() }} perubahan
            </span>
        </div>

        <div class="accordion" id="accordionHistory-{{ $jadwal->id }}">
            @foreach ($jadwal->historyPerubahan as $index => $history)
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header" id="heading-{{ $jadwal->id }}-{{ $index }}">
                        <button class="accordion-button collapsed d-flex justify-content-between" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $jadwal->id }}-{{ $index }}"
                            aria-expanded="false" aria-controls="collapse-{{ $jadwal->id }}-{{ $index }}">
                            <div>
                                <span class="badge bg-secondary me-2">#{{ $loop->iteration }}</span>
                                Perubahan pada {{ $history->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="ms-2">
                                @if ($history->status === 'disetujui')
                                    <span class="badge bg-success me-1"><i class="bi bi-check-circle"></i>
                                        Disetujui</span>
                                @elseif($history->status === 'ditolak')
                                    <span class="badge bg-danger me-1"><i class="bi bi-x-circle"></i> Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark me-1"><i class="bi bi-clock"></i>
                                        Diajukan</span>
                                @endif
                            </div>
                        </button>
                    </h2>
                    <div id="collapse-{{ $jadwal->id }}-{{ $index }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $jadwal->id }}-{{ $index }}"
                        data-bs-parent="#accordionHistory-{{ $jadwal->id }}">
                        <div class="accordion-body pt-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card border-danger border-1">
                                        <div class="card-header bg-danger bg-opacity-10 py-2">
                                            <h6 class="mb-0 text-white"><i class="bi bi-arrow-left"></i> Jadwal
                                                Sebelumnya</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2"><strong>Tanggal:</strong>
                                                <span class="text-danger">{{ $history->tanggal_lama }}</span>
                                            </p>
                                            <p class="mb-0"><strong>Jam:</strong>
                                                <span class="text-danger">{{ $history->jam_lama }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-success border-1">
                                        <div class="card-header bg-success bg-opacity-10 py-2">
                                            <h6 class="mb-0 text-white"><i class="bi bi-arrow-right"></i> Jadwal
                                                Perubahan</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2"><strong>Tanggal:</strong>
                                                <span class="text-success">{{ $history->tanggal_baru }}</span>
                                            </p>
                                            <p class="mb-0"><strong>Jam:</strong>
                                                <span class="text-success">{{ $history->jam_baru }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold"><i class="bi bi-chat-square-text"></i> Alasan Perubahan</h6>
                                <div class="bg-light p-3 rounded">
                                    {{ $history->alasan_perubahan ?: 'Tidak ada alasan yang dicantumkan' }}
                                </div>
                            </div>

                            {{-- Tampilkan catatan penolakan dari dosen jika ada --}}
                            @if ($history->status === 'ditolak' && $history->bimbingan && $history->bimbingan->catatanBimbingan->isNotEmpty())
                                @php
                                    $catatanPenolakan = $history->bimbingan->catatanBimbingan
                                        ->where('author_type', 'dosen')
                                        ->last();
                                @endphp

                                @if ($catatanPenolakan)
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-danger"><i class="bi bi-x-circle"></i> Catatan Penolakan
                                        </h6>
                                        <div class="bg-light p-3 rounded border border-danger">
                                            {{ $catatanPenolakan->catatan }}
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="d-flex justify-content-between align-items-center text-muted">
                                <small><i class="bi bi-clock"></i> Diubah
                                    {{ $history->created_at->diffForHumans() }}</small>
                                @if ($history->updated_at->ne($history->created_at))
                                    <small><i class="bi bi-arrow-repeat"></i> Diperbarui
                                        {{ $history->updated_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
