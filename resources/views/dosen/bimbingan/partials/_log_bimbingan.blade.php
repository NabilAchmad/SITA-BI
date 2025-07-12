{{-- Panel File Revisi Terbaru --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-arrow-down-fill text-primary me-2"></i>File Revisi Mahasiswa
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
            <p class="text-muted text-center mb-0 py-3">Mahasiswa belum mengunggah file revisi untuk sesi ini.</p>
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

{{-- Form Catatan Dosen --}}
<div class="mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Tulis Catatan /
                Feedback Baru</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('dosen.catatan.store', $tugasAkhir->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="catatan" class="form-control" rows="4"
                        placeholder="Tuliskan feedback, arahan, atau catatan untuk mahasiswa di sini..." required
                        style="resize: vertical; min-height: 120px;"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2"><i
                            class="bi bi-send me-2"></i>Kirim Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ✅ BARU & DIREVISI: Modal untuk menampilkan seluruh riwayat --}}
<div class="modal fade" id="riwayatLengkapModal" tabindex="-1" aria-labelledby="riwayatLengkapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="riwayatLengkapModalLabel"><i class="bi bi-collection-fill me-2"></i>Riwayat
                    Lengkap Bimbingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light p-4">
                @php
                    // Gabungkan catatan dan dokumen menjadi satu koleksi 'timeline' dan urutkan berdasarkan tanggal dibuat.
                    $timelineItems = collect($catatanList)->concat($riwayatDokumen)->sortBy('created_at');
                @endphp

                @forelse ($timelineItems as $item)
                    {{-- Tampilan untuk item TIPE CATATAN --}}
                    @if ($item instanceof \App\Models\CatatanBimbingan)
                        @php
                            $isDosen = $item->author_type === 'App\Models\Dosen';
                        @endphp
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-grow-1 {{ $isDosen ? 'text-end' : '' }}">
                                <small class="text-muted">{{ $item->author?->user?->name ?? 'User' }} •
                                    {{ $item->created_at->diffForHumans() }}</small>
                                <div class="message-bubble {{ $isDosen ? 'dosen-bubble' : 'mahasiswa-bubble' }} mt-1">
                                    <p class="mb-0 lh-base" style="white-space: pre-wrap;">{{ $item->catatan }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Tampilan untuk item TIPE DOKUMEN --}}
                    @elseif ($item instanceof \App\Models\DokumenTa)
                        <div class="timeline-event-upload my-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="upload-icon-sm"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-semibold">
                                        Mahasiswa mengunggah file baru:
                                        <a href="{{ asset('storage/' . $item->file_path) }}"
                                            download="{{ $item->nama_file_asli }}">
                                            {{ $item->nama_file_asli }}
                                        </a>
                                    </p>
                                    <small class="text-muted">{{ $item->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-5">
                        <div class="empty-icon mb-3"><i class="bi bi-chat-square-dots"></i></div>
                        <h6 class="text-muted mb-2">Belum ada riwayat aktivitas</h6>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .message-bubble {
            border-radius: 1rem;
            padding: 0.75rem 1rem;
            display: inline-block;
            max-width: 80%;
        }

        .mahasiswa-bubble {
            background-color: #e9ecef;
            text-align: left;
            border-bottom-left-radius: 0.25rem;
        }

        .dosen-bubble {
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
