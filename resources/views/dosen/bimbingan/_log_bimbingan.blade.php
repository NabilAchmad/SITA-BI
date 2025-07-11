{{-- Log Bimbingan Terpusat (Versi Final Anti-Error & Informatif) --}}
<div class="mb-4">
    @forelse ($catatanList as $catatan)
        @php
            // STYLE: Menentukan apakah penulis adalah Dosen untuk styling kondisional
            $isDosen = $catatan->author_type === 'App\Models\Dosen';
        @endphp
        <div class="mb-4">
            {{-- Card Utama --}}
            {{-- STYLE: Menambahkan class untuk alignment berbeda antara dosen dan mahasiswa --}}
            <div class="card shadow-sm border-0 overflow-hidden {{ $isDosen ? 'dosen-post' : 'mahasiswa-post' }}">

                {{-- Header dengan Avatar dan Info User --}}
                {{-- STYLE: Menggunakan flex-row-reverse untuk Dosen agar avatar di kanan --}}
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center gap-3 {{ $isDosen ? 'flex-row-reverse' : '' }}">

                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if ($catatan->author?->user)
                                @if ($isDosen)
                                    <div class="avatar-circle bg-primary text-white"
                                        title="Dosen: {{ $catatan->author->user->name }}">
                                        <i class="bi bi-person-video3 fs-5"></i>
                                    </div>
                                @else
                                    <div class="avatar-circle bg-info text-white"
                                        title="Mahasiswa: {{ $catatan->author->user->name }}">
                                        <i class="bi bi-person fs-5"></i>
                                    </div>
                                @endif
                            @else
                                <div class="avatar-circle bg-danger text-white" title="Data tidak lengkap!">
                                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info User --}}
                        {{-- STYLE: Menyesuaikan alignment teks untuk Dosen --}}
                        <div class="flex-grow-1 {{ $isDosen ? 'text-end' : '' }}">
                            <div
                                class="d-flex align-items-center gap-2 mb-1 {{ $isDosen ? 'justify-content-end' : '' }}">
                                <h6 class="mb-0 fw-bold text-dark">
                                    {{ $catatan->author?->user?->name ?? 'Pengguna tidak ditemukan' }}
                                </h6>
                                @if ($isDosen)
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">
                                        <i class="bi bi-mortarboard me-1"></i>Dosen
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill">
                                        <i class="bi bi-person-badge me-1"></i>Mahasiswa
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $catatan->created_at->diffForHumans() }}
                            </small>
                        </div>

                        {{-- Error Badge jika ada masalah, diposisikan di ujung --}}
                        @if (!$catatan->author?->user)
                            <div class="flex-shrink-0 ms-auto">
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Error: {{ Str::afterLast($catatan->author_type, '\\') }} ID:
                                    {{ $catatan->author_id }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Body dengan Konten --}}
                <div class="card-body p-4">
                    {{-- FIX: Logika if/else yang benar untuk memisahkan tampilan file dan teks --}}
                    @if (str_starts_with($catatan->catatan, 'UPLOAD_ID:'))
                        @php
                            $dokumenId = Str::after($catatan->catatan, 'UPLOAD_ID:');
                            $dokumen = \App\Models\DokumenTa::find($dokumenId);
                        @endphp
                        {{-- File Upload Content --}}
                        <div class="upload-content">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="upload-icon">
                                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1 fw-semibold text-success">
                                        <i class="bi bi-check-circle-fill me-2"></i>File Baru Diunggah
                                    </h6>
                                    <p class="text-muted mb-2 small">
                                        Mahasiswa telah mengunggah dokumen baru untuk ditinjau.
                                    </p>
                                    @if ($dokumen)
                                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank"
                                            class="btn btn-outline-success btn-sm rounded-pill">
                                            <i class="bi bi-download me-1"></i> Unduh File
                                            ({{ \Illuminate\Support\Str::limit($dokumen->nama_file, 20) }})
                                        </a>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                            <i class="bi bi-exclamation-triangle me-1"></i>File tidak ditemukan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Text Content --}}
                        <div class="text-content">
                            {{-- STYLE: Message bubble menyesuaikan dengan pengirim (Dosen/Mahasiswa) --}}
                            <div class="message-bubble {{ $isDosen ? 'dosen-bubble' : 'mahasiswa-bubble' }}">
                                <p class="mb-0 lh-base" style="white-space: pre-wrap;">{{ $catatan->catatan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon mb-3">
                    <i class="bi bi-chat-square-dots"></i>
                </div>
                <h6 class="text-muted mb-2">Belum ada aktivitas bimbingan</h6>
                <p class="text-muted small mb-0">Mulai diskusi dengan menulis catatan pertama.</p>
            </div>
        </div>
    @endforelse
</div>

<hr class="my-4">

{{-- Form Catatan Dosen --}}
<div class="mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Tulis Catatan / Feedback Baru
            </h6>
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
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="bi bi-send me-2"></i>Kirim Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* --- General Card & Layout --- */
        .card {
            transition: all 0.2s ease;
            border-radius: 0.75rem !important;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-2px);
        }

        .mahasiswa-post .card-body {
            padding-left: 2rem;
        }

        .dosen-post .card-body {
            padding-right: 2rem;
        }

        /* --- Author Identification --- */
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* --- Content Bubbles (Text & Upload) --- */
        .message-bubble {
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            position: relative;
            display: inline-block;
            max-width: 100%;
        }

        .mahasiswa-bubble {
            background: #f1f3f5;
            border-left: 4px solid #17a2b8;
            /* Info color */
            text-align: left;
        }

        .dosen-bubble {
            background: #e7f5ff;
            border-right: 4px solid #007bff;
            /* Primary color */
            text-align: left;
            /* Teks tetap rata kiri untuk keterbacaan */
        }

        .dosen-post .text-content {
            text-align: right;
            /* Aligns the bubble to the right */
        }

        .message-bubble p {
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* --- Upload Content Styling --- */
        .upload-content {
            background: #e8f5e9;
            border: 1px dashed #28a745;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .upload-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(40, 167, 69, 0.3);
        }

        /* --- Empty State --- */
        .empty-state {
            max-width: 300px;
            margin: 0 auto;
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

        /* --- Form Improvements --- */
        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.75rem rgba(0, 123, 255, 0.3);
        }

        .btn-outline-success:hover {
            transform: translateY(-1px);
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .avatar-circle {
                width: 45px;
                height: 45px;
            }

            .upload-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .card-body {
                padding: 1.25rem !important;
            }

            .mahasiswa-post .card-body,
            .dosen-post .card-body {
                padding: 1.25rem !important;
            }
        }
    </style>
@endpush
