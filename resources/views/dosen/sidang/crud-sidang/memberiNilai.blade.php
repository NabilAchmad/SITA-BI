<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\sidang\crud-sidang\memberiNilai.blade.php -->

<div class="mb-4">
    <h5>Form Memberi Nilai Sidang</h5>
</div>

<form>
    <div class="mb-3">
        <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" id="namaMahasiswa" value="Ahmad Fauzi" readonly>
    </div>
    <div class="mb-3">
        <label for="judulSidang" class="form-label">Judul Sidang</label>
        <input type="text" class="form-control" id="judulSidang" value="Sistem Informasi Akademik" readonly>
    </div>
    <div class="mb-3">
        <label for="nilai" class="form-label">Nilai Sidang</label>
        <input type="number" class="form-control" id="nilai" min="0" max="100" placeholder="Masukkan nilai sidang">
    </div>
    <div class="mb-3">
        <label for="catatan" class="form-label">Catatan</label>
        <textarea class="form-control" id="catatan" rows="3" placeholder="Masukkan catatan penilaian"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
    <button type="reset" class="btn btn-secondary">Batal</button>
</form>