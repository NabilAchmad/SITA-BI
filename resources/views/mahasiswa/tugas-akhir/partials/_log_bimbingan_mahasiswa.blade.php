{{-- Tampilan ini sangat mirip dengan log bimbingan di sisi dosen --}}
<div class="mb-4">
    @forelse ($catatanList as $catatan)
        {{-- Logika perulangan sama persis dengan _log_bimbingan.blade.php milik dosen --}}
        {{-- Ini memastikan mahasiswa dan dosen melihat timeline yang identik --}}
        <div class="d-flex gap-3 mb-4">
            <div class="flex-shrink-0">
                @if ($catatan->author?->user)
                    @if ($catatan->author_type === 'App\Models\Dosen')
                        <div class="log-icon bg-primary text-white shadow-sm"
                            title="Dosen: {{ $catatan->author->user->name }}"><i class="bi bi-person-video3"></i></div>
                    @else
                        <div class="log-icon bg-secondary text-white shadow-sm"
                            title="Mahasiswa: {{ $catatan->author->user->name }}"><i class="bi bi-person"></i></div>
                    @endif
                @else
                    <div class="log-icon bg-danger text-white shadow-sm" title="Data tidak lengkap!"><i
                            class="bi bi-exclamation-triangle-fill"></i></div>
                @endif
            </div>
            <div class="flex-grow-1">
                <div class="card shadow-sm border-light">
                    <div class="card-header bg-white py-2 px-3 border-0">
                        <strong
                            class="text-dark">{{ $catatan->author?->user?->name ?? 'Pengguna tidak ditemukan' }}</strong>
                        <small class="text-muted">• {{ $catatan->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="card-body p-3">
                        @if (str_starts_with($catatan->catatan, 'UPLOAD_ID:'))
                            @php
                                $dokumenId = Str::after($catatan->catatan, 'UPLOAD_ID:');
                                $dokumen = \App\Models\DokumenTa::find($dokumenId);
                            @endphp
                            <div class="d-flex align-items-center gap-3 bg-light-success p-3 rounded">
                                <i class="bi bi-file-earmark-arrow-up-fill fs-2 text-success"></i>
                                <div>
                                    <p class="mb-1 fw-semibold">Anda mengunggah file baru</p>
                                    @if ($dokumen)
                                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary rounded-pill">Lihat File</a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $catatan->catatan }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5"><i class="bi bi-chat-square-dots fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada aktivitas bimbingan.</p>
        </div>
    @endforelse
</div>

<hr>

{{-- Form untuk Mahasiswa Memberi Catatan Baru --}}
<div class="mt-4">
    <h6 class="fw-bold mb-3">Tulis Pertanyaan / Catatan untuk Dosen</h6>
    <form action="{{ route('mahasiswa.tugas-akhir.catatan.store', $tugasAkhir->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <textarea name="catatan" class="form-control" rows="3"
                placeholder="Tuliskan pertanyaan atau catatan singkat di sini..." required></textarea>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-info rounded-pill px-4 text-white">
                <i class="bi bi-send me-1"></i> Kirim
            </button>
        </div>
    </form>
</div>
