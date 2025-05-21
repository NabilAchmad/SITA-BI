
<div class="container">
    <h1>Form Pendaftaran Sidang Tugas Akhir</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" class="form-control" id="nim" name="nim" required>
        </div>
        <div class="mb-3">
            <label for="judul_ta" class="form-label">Judul Tugas Akhir</label>
            <input type="text" class="form-control" id="judul_ta" name="judul_ta" required>
        </div>
        <div class="mb-3">
            <label for="dosen_pembimbing" class="form-label">Dosen Pembimbing 1</label>
            <input type="text" class="form-control" id="dosen_pembimbing" name="dosen_pembimbing" required>
        </div>
        <div class="mb-3">
            <label for="dosen_pembimbing" class="form-label">Dosen Pembimbing 2</label>
            <input type="text" class="form-control" id="dosen_pembimbing" name="dosen_pembimbing" required>
        </div>
        <div class="mb-3">
            <label for="jumlah_bimbingan" class="form-label">Jumlah Bimbingan yang Telah Diikuti</label>
            <input type="number" class="form-control" id="jumlah_bimbingan" name="jumlah_bimbingan" min="0" required>
        </div>
        <div class="mb-3">
            <label for="file_ta" class="form-label">Upload File Tugas Akhir</label>
            <input type="file" class="form-control" id="file_ta" name="file_ta" required>
        </div>
        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
</div>
