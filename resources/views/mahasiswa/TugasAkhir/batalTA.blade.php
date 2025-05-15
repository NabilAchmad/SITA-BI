<form action="{{ route('mahasiswa.TugasAkhir.batalTA') }}" method="POST" class="p-3">
    @csrf
    <h3 class="mb-3">Batalkan Tugas Akhir</h3>
    <p>Apakah Anda yakin ingin membatalkan tugas akhir Anda? </p>

    <div class="mb-3">
        <label for="alasan" class="form-label">Alasan Pembatalan</label>
        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-danger">Batalkan</button>
    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Kembali</button>    
</form>
