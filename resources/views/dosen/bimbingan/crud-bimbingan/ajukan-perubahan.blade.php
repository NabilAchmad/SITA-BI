<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\crud-bimbingan\ajukan-perubahan.blade.php -->
<!-- Modal Tolak Bimbingan -->
<div class="modal fade" id="tolakBimbinganModal" tabindex="-1" aria-labelledby="tolakBimbinganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('bimbingan.tolak') }}">
            @csrf
            <input type="hidden" name="bimbingan_id" id="bimbingan_id_input"> {{-- ID bimbingan --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tolakBimbinganModalLabel">Komentar Penolakan Bimbingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar_penolakan" class="form-label">Alasan Penolakan</label>
                        <textarea name="komentar_penolakan" id="komentar_penolakan" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk memasukkan ID ke dalam input tersembunyi saat tombol tolak diklik
    const modal = document.getElementById('tolakBimbinganModal');
    if(modal){
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            modal.querySelector('#bimbingan_id_input').value = id;
        });
    }
</script>
@endpush