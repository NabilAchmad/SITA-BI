<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\tawaranTopik\crud-tawaranTopik\mengubah-tawaranTopik.blade.php -->

<div class="mb-4">
    <h5>Form Ubah Tawaran Topik</h5>
</div>

<form>
    <div class="mb-3">
        <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" id="namaMahasiswa" value="Ahmad Fauzi" readonly>
    </div>
    <div class="mb-3">
        <label for="topik" class="form-label">Topik</label>
        <input type="text" class="form-control" id="topik" value="Pengembangan Sistem Informasi">
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" rows="3">Membuat aplikasi sistem informasi akademik berbasis web.</textarea>
    </div>
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal Pengajuan</label>
        <input type="date" class="form-control" id="tanggal" value="2025-05-21">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status">
            <option selected>Menunggu Persetujuan</option>
            <option>Disetujui</option>
            <option>Ditolak</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <button type="button" class="btn btn-secondary">Batal</button>
</form>