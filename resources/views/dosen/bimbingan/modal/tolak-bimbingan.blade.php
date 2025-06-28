<!-- components/modals/tolak-bimbingan.blade.php -->
<div class="modal fade" id="modalTolakBimbingan-{{ $bimbingan->id }}" tabindex="-1"
    aria-labelledby="modalTolakLabel-{{ $bimbingan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('bimbingan.tolak', $bimbingan->id) }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="bimbingan_id" value="{{ $bimbingan->id }}">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalTolakLabel-{{ $bimbingan->id }}">
                    Tolak Bimbingan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="komentar_penolakan_{{ $bimbingan->id }}" class="form-label">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea name="komentar_penolakan" id="komentar_penolakan_{{ $bimbingan->id }}" class="form-control" rows="4"
                        required placeholder="Tuliskan alasan penolakan..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle me-1"></i> Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>
