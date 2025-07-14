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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="riwayatLengkapModalLabel">
                    <i class="bi bi-chat-dots-fill me-2"></i>Riwayat Lengkap Bimbingan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light" style="max-height: 500px; overflow-y: auto;">
                @forelse ($timelineItems as $item)
                    {{-- Tampilan untuk item TIPE CATATAN --}}
                    @if ($item instanceof \App\Models\CatatanBimbingan)
                        @php
                            $isDosen = $item->author_type === 'App\Models\Dosen';
                        @endphp
                        <div class="d-flex gap-3 mb-4 {{ $isDosen ? 'flex-row-reverse' : '' }}">
                            <!-- Avatar -->
                            <div class="avatar-container">
                                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $isDosen ? 'bg-primary' : 'bg-success' }}"
                                    style="width: 40px; height: 40px;">
                                    <i
                                        class="bi {{ $isDosen ? 'bi-person-fill-gear' : 'bi-person-fill' }} text-white"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-grow-1" style="max-width: 70%;">
                                <div
                                    class="message-bubble {{ $isDosen ? 'bg-primary text-white' : 'bg-white border' }} p-3 rounded-3 shadow-sm">
                                    <p class="mb-0 lh-base" style="white-space: pre-wrap;">{{ $item->catatan }}</p>
                                </div>
                                <div class="mt-1 {{ $isDosen ? 'text-end' : '' }}">
                                    <small class="text-muted">
                                        <strong>{{ $item->author?->user?->name ?? 'User' }}</strong> •
                                        {{ $item->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Tampilan untuk item TIPE DOKUMEN --}}
                    @elseif ($item instanceof \App\Models\DokumenTa)
                        <div class="upload-event bg-white border rounded-3 p-3 mb-3 shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-info d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-file-earmark-arrow-up-fill text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-semibold">
                                        <i class="bi bi-person-fill text-success me-1"></i>Mahasiswa mengunggah file
                                        baru:
                                    </p>
                                    <a href="{{ asset('storage/' . $item->file_path) }}"
                                        download="{{ $item->nama_file_asli }}" class="text-decoration-none">
                                        <i class="bi bi-download me-1"></i>{{ $item->nama_file_asli }}
                                    </a>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i
                                                class="bi bi-clock me-1"></i>{{ $item->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-5">
                        <div class="text-muted mb-3" style="font-size: 3rem;">
                            <i class="bi bi-chat-square-dots"></i>
                        </div>
                        <h6 class="text-muted mb-2">Belum ada riwayat aktivitas</h6>
                        <p class="text-muted mb-0">Mulai bimbingan dengan mengirim pesan atau mengunggah dokumen</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .message-bubble {
            position: relative;
        }

        .message-bubble.bg-primary {
            border-bottom-right-radius: 0.5rem !important;
        }

        .message-bubble.bg-white {
            border-bottom-left-radius: 0.5rem !important;
        }

        .avatar-container {
            flex-shrink: 0;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush
