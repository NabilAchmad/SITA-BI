<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-pencil-square me-2 fs-4"></i>
      <h4 class="mb-0">Revisi Tugas Akhir</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="judulRevisi" class="form-label fw-semibold">Judul Revisi</label>
          <input type="text" class="form-control" id="judulRevisi" name="judul" placeholder="Masukkan judul revisi">
        </div>
  
        <div class="mb-3">
          <label for="deskripsiRevisi" class="form-label fw-semibold">Deskripsi Revisi</label>
          <textarea class="form-control" id="deskripsiRevisi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi revisi"></textarea>
        </div>
  
        <div class="row">

          <div class="col-md-6 mb-3">
            <label for="fileRevisi" class="form-label fw-semibold">Upload File Revisi</label>
            <input class="form-control" type="file" id="fileRevisi" name="file">
          </div>
        </div>
  
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success me-2">
            <i class="bi bi-check-circle me-1"></i> Simpan
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i> Batal
          </button>
        </div>
      </form>
    </div>
  </div>
  