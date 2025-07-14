{{-- Panel File Revisi Terbaru --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-arrow-down-fill text-primary me-2"></i>File Terbaru
        </h6>
    </div>
    <div class="card-body">
        @if ($dokumenTerbaru)
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <p class="fw-semibold mb-1" title="{{ $dokumenTerbaru->nama_file_asli }}">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        {{ Str::limit($dokumenTerbaru->nama_file_asli, 40) ?? 'Dokumen Revisi' }}
                    </p>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i> Diunggah:
                        {{ $dokumenTerbaru->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="{{ asset('storage/' . $dokumenTerbaru->file_path) }}"
                        class="btn btn-success rounded-pill px-4" download="{{ $dokumenTerbaru->nama_file_asli }}">
                        <i class="bi bi-download me-1"></i> Download File
                    </a>
                </div>
            </div>
        @else
            <p class="text-muted text-center mb-0 py-3">Anda belum mengunggah file revisi untuk sesi ini.</p>
        @endif
    </div>
</div>

{{-- Tombol untuk melihat riwayat lengkap --}}
<div class="text-center mb-4">
    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-toggle="modal"
        data-bs-target="#riwayatLengkapModal">
        <i class="bi bi-collection me-1"></i> Lihat Riwayat Lengkap Bimbingan
    </button>
</div>

<hr class="my-4">

{{-- Form untuk Mahasiswa Memberi Catatan Baru --}}
<div class="mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Tulis Pertanyaan /
                Catatan untuk Dosen</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('mahasiswa.tugas-akhir.catatan.store', $tugasAkhir->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="pembimbing" class="form-label fw-semibold">Kirim ke Pembimbing</label>
                    <select name="bimbingan_ta_id" class="form-select" required>
                        <option value="">-- Pilih Pembimbing --</option>
                        @if ($pembimbing1)
                            <option value="{{ $pembimbing1->id }}">Pembimbing 1 - {{ $pembimbing1->dosen->user->name }}
                            </option>
                        @endif
                        @if ($pembimbing2)
                            <option value="{{ $pembimbing2->id }}">Pembimbing 2 - {{ $pembimbing2->dosen->user->name }}
                            </option>
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <textarea name="catatan" class="form-control" rows="4"
                        placeholder="Tuliskan catatan untuk dosen pembimbing yang dipilih..." required
                        style="resize: vertical; min-height: 120px;"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="bi bi-send me-2"></i> Kirim Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal dengan Tab untuk setiap Pembimbing --}}
{{-- Modal Riwayat Bimbingan --}}
<div class="modal fade" id="riwayatLengkapModal" tabindex="-1" aria-labelledby="riwayatLengkapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary bg-opacity-10">
                <h5 class="modal-title fw-semibold" id="riwayatLengkapModalLabel">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Bimbingan Tugas Akhir
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-justified" id="bimbinganTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-medium py-3" id="pembimbing1-tab" data-bs-toggle="tab"
                            data-bs-target="#pembimbing1-content" type="button" role="tab"
                            aria-controls="pembimbing1-content" aria-selected="true">
                            <i class="bi bi-person-vcard me-2"></i>Pembimbing 1
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-medium py-3" id="pembimbing2-tab" data-bs-toggle="tab"
                            data-bs-target="#pembimbing2-content" type="button" role="tab"
                            aria-controls="pembimbing2-content" aria-selected="false">
                            <i class="bi bi-person-vcard me-2"></i>Pembimbing 2
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content bg-light" id="bimbinganTabContent">
                    <!-- Pembimbing 1 Content -->
                    <div class="tab-pane fade show active" id="pembimbing1-content" role="tabpanel"
                        aria-labelledby="pembimbing1-tab">
                        <div class="p-4">
                            @php
                                $catatanP1 = collect($catatanList)->filter(function ($catatan) use ($pembimbing1) {
                                    if (!$catatan->bimbinganTa) {
                                        return false;
                                    }

                                    if ($catatan->author_type === 'App\Models\Dosen') {
                                        return $catatan->author_id == $pembimbing1->dosen_id;
                                    }

                                    if ($catatan->author_type === 'App\Models\Mahasiswa') {
                                        return $catatan->bimbinganTa->dosen_id == $pembimbing1->dosen_id;
                                    }

                                    return false;
                                });

                                $timelineP1 = $catatanP1->concat($riwayatDokumen)->sortBy('created_at');
                            @endphp

                            @if ($timelineP1->count() > 0)
                                <div class="timeline">
                                    @foreach ($timelineP1 as $item)
                                        @include('mahasiswa.tugas-akhir.partials._timeline_item', [
                                            'item' => $item,
                                            'pembimbingId' => $pembimbing1->dosen_id,
                                        ])
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 bg-white rounded-3">
                                    <i class="bi bi-journal-text display-6 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada riwayat bimbingan dengan Pembimbing 1</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pembimbing 2 Content -->
                    <div class="tab-pane fade" id="pembimbing2-content" role="tabpanel"
                        aria-labelledby="pembimbing2-tab">
                        <div class="p-4">
                            @php
                                $catatanP2 = collect($catatanList)->filter(function ($catatan) use ($pembimbing2) {
                                    if (!$catatan->bimbinganTa) {
                                        return false;
                                    }

                                    if ($catatan->author_type === 'App\Models\Dosen') {
                                        return $catatan->author_id == $pembimbing2->dosen_id;
                                    }

                                    if ($catatan->author_type === 'App\Models\Mahasiswa') {
                                        return $catatan->bimbinganTa->dosen_id == $pembimbing2->dosen_id;
                                    }

                                    return false;
                                });

                                $timelineP2 = $catatanP2->concat($riwayatDokumen)->sortBy('created_at');
                            @endphp

                            @if ($timelineP2->count() > 0)
                                <div class="timeline">
                                    @foreach ($timelineP2 as $item)
                                        @include('mahasiswa.tugas-akhir.partials._timeline_item', [
                                            'item' => $item,
                                            'pembimbingId' => $pembimbing2->dosen_id,
                                        ])
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 bg-white rounded-3">
                                    <i class="bi bi-journal-text display-6 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada riwayat bimbingan dengan Pembimbing 2</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 1.5rem;
            border-left: 2px solid #dee2e6;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.7rem;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #0d6efd;
            border: 2px solid white;
        }

        .message-bubble {
            border-radius: 1rem;
            padding: 0.75rem 1rem;
            display: inline-block;
            max-width: 100%;
        }

        /* Bubble untuk pesan "mereka" (dosen) */
        .their-bubble {
            background-color: #e9ecef;
            text-align: left;
            border-bottom-left-radius: 0.25rem;
        }

        /* Bubble untuk pesan "saya" (mahasiswa) */
        .my-bubble {
            background-color: #cfe2ff;
            text-align: left;
            border-bottom-right-radius: 0.25rem;
        }

        .timeline-event-upload {
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .upload-icon-sm {
            font-size: 1.2rem;
            color: #28a745;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #6c757d;
            font-size: 2rem;
        }
    </style>
@endpush
