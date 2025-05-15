<form action="" method="POST">
    @csrf
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Tugas Akhir</label>
        <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul tugas akhir" required>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi tugas akhir" required></textarea>
    </div>

    <div class="mb-3">
        <label for="file_proposal" class="form-label">Upload Proposal</label>
        <input type="file" class="form-control" id="file_proposal" name="file_proposal" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajukan</button>
</form>