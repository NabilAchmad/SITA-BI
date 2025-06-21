<!-- Modal Revisi Tugas Akhir -->
<div class="modal fade" id="revisiTAModal" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('ta.revisi') }}">
            @csrf
            <input type="hidden" name="ta_id" id="ta_id_input"> {{-- ID kemajuan TA --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiModalLabel">Revisi Tugas Akhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar_revisi" class="form-label">Komentar Revisi</label>
                        <textarea name="komentar_revisi" id="komentar_revisi" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk memasukkan ID ke dalam input tersembunyi saat tombol revisi diklik
    const modal = document.getElementById('revisiTAModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        modal.querySelector('#ta_id_input').value = id;
    });
</script>
@endpush
