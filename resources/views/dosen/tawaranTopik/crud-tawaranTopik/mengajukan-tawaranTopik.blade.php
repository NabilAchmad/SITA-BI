<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\tawaranTopik\crud-tawaranTopik\mengajukan-tawaranTopik.blade.php -->

<div class="mb-4">
    <h5>Form Mengajukan Tawaran Topik</h5>
</div>

<form>
    <div class="mb-3">
        <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" id="namaMahasiswa" placeholder="Masukkan nama mahasiswa">
    </div>
    <div class="mb-3">
        <label for="topik" class="form-label">Topik</label>
        <input type="text" class="form-control" id="topik" placeholder="Masukkan topik">
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" rows="3" placeholder="Masukkan deskripsi topik"></textarea>
    </div>
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal Pengajuan</label>
        <input type="date" class="form-control" id="tanggal">
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
    <button type="submit" class="btn btn-primary">Ajukan Topik</button>
    <button type="reset" class="btn btn-secondary">Batal</button>
</form>