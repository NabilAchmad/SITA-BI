<style>
  .text-primary-donk {
    color: #004085 !important;
  }
</style>

<h1 class="text-center text-primary-donk">Ajukan Tugas Akhir</h1>

<form action="{{ route('tugasAkhir.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Tugas Akhir</label>
        <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul tugas akhir" required>
    </div>
    <div class="mb-3">
        <label for="abstrak" class="form-label">Abstrak</label>
        <textarea class="form-control" id="abstrak" name="abstrak" rows="3" placeholder="Masukkan abstrak tugas akhir" required></textarea>
    </div>
    <div class="mb-3">
        <label for="file_proposal" class="form-label">Upload Proposal</label>
        <input type="file" class="form-control" id="file_proposal" name="file_proposal" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajukan</button>
</form>
