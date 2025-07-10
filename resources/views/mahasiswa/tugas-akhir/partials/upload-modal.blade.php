<div class="modal fade" id="uploadFileModal{{ $tugasAkhir->id }}" tabindex="-1"
    aria-labelledby="uploadFileModalLabel{{ $tugasAkhir->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold mb-0" id="uploadFileModalLabel{{ $tugasAkhir->id }}">
                    <i class="fas fa-cloud-upload-alt me-2"></i> Upload Dokumen Tugas Akhir
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('mahasiswa.tugas-akhir.upload-file', $tugasAkhir->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">

                    {{-- ✅ PERBAIKAN: Tambahkan dropdown untuk memilih jenis dokumen --}}
                    <div class="mb-4">
                        <label for="jenis_dokumen_{{ $tugasAkhir->id }}" class="form-label fw-semibold">Jenis
                            Dokumen</label>
                        <select name="jenis_dokumen" id="jenis_dokumen_{{ $tugasAkhir->id }}"
                            class="form-select form-select-lg" required>
                            <option value="" disabled selected>-- Pilih Jenis Dokumen --</option>
                            <option value="proposal">Proposal</option>
                            <option value="draft">Draft</option>
                            <option value="final">Final</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="file_upload_{{ $tugasAkhir->id }}" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1"></i> Pilih File
                        </label>
                        <input type="file" name="file" id="file_upload_{{ $tugasAkhir->id }}"
                            class="form-control form-control-lg" accept=".pdf,.doc,.docx" required>
                        <div class="form-text mt-2">
                            <small><i class="fas fa-info-circle me-1"></i> Format: PDF, DOC, DOCX (Maks: 25MB)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="fas fa-upload me-2"></i> Upload Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
