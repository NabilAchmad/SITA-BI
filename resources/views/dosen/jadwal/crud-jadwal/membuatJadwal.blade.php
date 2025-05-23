<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\jadwal\crud-jadwal\membuatJadwal.blade.php -->

<div class="mb-4">
    <h5>Form Membuat Jadwal Bimbingan</h5>
</div>

<form>
    <div class="mb-3">
        <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" id="namaMahasiswa" placeholder="Masukkan nama mahasiswa">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Bimbingan</label>
        <input type="text" class="form-control" id="judul" placeholder="Masukkan judul bimbingan">
    </div>
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal</label>
        <input type="date" class="form-control" id="tanggal">
    </div>
    <div class="mb-3">
        <label for="waktu" class="form-label">Waktu</label>
        <input type="time" class="form-control" id="waktu">
    </div>
    <div class="mb-3">
        <label for="gedung" class="form-label">Gedung</label>
        <input type="text" class="form-control" id="gedung" placeholder="Masukkan nama gedung">
    </div>
    <div class="mb-3">
        <label for="ruangan" class="form-label">Ruangan</label>
        <input type="text" class="form-control" id="ruangan" placeholder="Masukkan nomor ruangan">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status">
            <option selected disabled>Pilih status</option>
            <option>Menunggu Persetujuan</option>
            <option>Disetujui</option>
            <option>Ditolak</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Buat Jadwal</button>
    <button type="reset" class="btn btn-secondary">Batal</button>
</form>