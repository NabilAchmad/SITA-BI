<h2 class="text-center mb-2 fw-bold">Form Berita Acara</h2>
<form action="" method="POST" class="mx-auto" style="max-width: 700px;">
    @csrf

    <div class="mb-3">
        <label for="jenis_kegiatan" class="form-label fw-semibold">Jenis Kegiatan</label>
        <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan" required>
            <option value="" disabled selected>Pilih jenis kegiatan</option>
            <option value="sidang">Sidang</option>
            <option value="sempro">Seminar Proposal (Sempro)</option>
            <option value="semhas">Seminar Hasil (Semhas)</option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nama_mahasiswa" class="form-label fw-semibold">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa"
                placeholder="Masukkan nama mahasiswa" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nim" class="form-label fw-semibold">NIM</label>
            <input type="text" class="form-control" id="nim" name="nim"
                placeholder="Masukkan NIM mahasiswa" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="judul_tugas_akhir" class="form-label fw-semibold">Judul Tugas Akhir</label>
        <textarea class="form-control" id="judul_tugas_akhir" name="judul_tugas_akhir" rows="3"
            placeholder="Masukkan judul tugas akhir" required></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal" class="form-label fw-semibold">Tanggal Kegiatan</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="waktu" class="form-label fw-semibold">Waktu Kegiatan</label>
            <input type="time" class="form-control" id="waktu" name="waktu" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="tempat" class="form-label fw-semibold">Tempat Kegiatan</label>
        <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Masukkan tempat kegiatan"
            required>
    </div>

    <div class="mb-3">
        <label for="dosen_penguji" class="form-label fw-semibold">Dosen Penguji</label>
        <input type="text" class="form-control" id="dosen_penguji" name="dosen_penguji"
            placeholder="Masukkan nama dosen penguji" required>
    </div>

    <div class="mb-4">
        <label for="hasil" class="form-label fw-semibold">Hasil Kegiatan</label>
        <select class="form-select" id="hasil" name="hasil" required>
            <option value="" disabled selected>Pilih hasil kegiatan</option>
            <option value="lulus">Lulus</option>
            <option value="revisi">Revisi</option>
            <option value="tidak_lulus">Tidak Lulus</option>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary px-4">Simpan Berita Acara</button>
    </div>
</form>
