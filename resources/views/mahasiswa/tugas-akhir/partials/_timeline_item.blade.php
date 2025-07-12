@if ($item instanceof \App\Models\CatatanBimbingan)
    @php
        $isMe = $item->author_type === 'App\Models\Mahasiswa';
        $bgColor = $isMe ? 'bg-primary' : 'bg-light';
        $textColor = $isMe ? 'text-white' : 'text-dark';
    @endphp
    <div class="timeline-item">
        <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-3">
            <div class="d-flex flex-column" style="max-width: 85%">
                <div
                    class="d-flex align-items-center {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-1">
                    <span class="badge bg-secondary bg-opacity-10 text-white rounded-pill small">
                        {{ $item->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
                <div class="message-bubble {{ $bgColor }} {{ $textColor }} p-3 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-person-circle"></i>
                        <span class="fw-medium">{{ $item->author?->user?->name ?? 'User' }}</span>
                    </div>
                    <p class="mb-0 lh-base" style="white-space: pre-wrap;">{{ $item->catatan }}</p>
                </div>
            </div>
        </div>
    </div>
@elseif ($item instanceof \App\Models\DokumenTa)
    <div class="timeline-item">
        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 shadow-sm border">
            <div class="bg-primary bg-opacity-10 p-2 rounded-2">
                <i class="bi bi-file-earmark-arrow-up text-white"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 fw-semibold">Dokumen Diunggah</p>
                <a href="{{ asset('storage/' . $item->file_path) }}" download="{{ $item->nama_file_asli }}"
                    class="d-block text-decoration-none mb-1">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    {{ $item->nama_file_asli }}
                </a>
                <small class="text-muted">
                    {{ $item->created_at->format('d M Y \p\u\k\u\l H:i') }}
                </small>
            </div>
        </div>
    </div>
@endif
