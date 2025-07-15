<div class="modal fade" id="uploadFileModal{{ $tugasAkhir->id }}" tabindex="-1"
    aria-labelledby="uploadFileModalLabel{{ $tugasAkhir->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold mb-0" id="uploadFileModalLabel{{ $tugasAkhir->id }}">
                    <i class="fas fa-cloud-upload-alt me-2"></i> Ajukan Bimbingan & Upload Revisi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            {{-- 
              ✅ PERBAIKAN 1: Mengubah action form agar menunjuk ke route yang benar.
              Pastikan di file routes/web.php Anda, nama route-nya adalah 'mahasiswa.tugas-akhir.ajukan-bimbingan'.
            --}}
            <form action="{{ route('mahasiswa.tugas-akhir.upload-file', $tugasAkhir->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">

                    <div class="mb-4">
                        <label for="tipe_dokumen_{{ $tugasAkhir->id }}" class="form-label fw-semibold">Tipe
                            Dokumen</label>
                        {{-- ✅ PERBAIKAN 2: Mengubah name dari 'jenis_dokumen' menjadi 'tipe_dokumen' --}}
                        <select name="tipe_dokumen" id="tipe_dokumen_{{ $tugasAkhir->id }}"
                            class="form-select form-select-lg" required readonly onfocus="this.blur()"
                            style="pointer-events: none; background-color: #e9ecef;">
                            <option value="bimbingan" selected>Bimbingan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="file_bimbingan_{{ $tugasAkhir->id }}" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1"></i> Pilih File Revisi
                        </label>
                        {{-- ✅ PERBAIKAN 3: Mengubah name dari 'file' menjadi 'file_bimbingan' --}}
                        <input type="file" name="file_bimbingan" id="file_bimbingan_{{ $tugasAkhir->id }}"
                            class="form-control form-control-lg" accept=".pdf,.doc,.docx" required>
                        <div class="form-text mt-2">
                            <small><i class="fas fa-info-circle me-1"></i> Format: PDF, DOC, DOCX (Maks: 25MB)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan_upload_{{ $tugasAkhir->id }}" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i> Catatan (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan_upload_{{ $tugasAkhir->id }}" class="form-control" rows="3"
                            placeholder="Tinggalkan pesan singkat untuk dosen jika perlu..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="fas fa-upload me-2"></i> Upload & Ajukan Bimbingan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
