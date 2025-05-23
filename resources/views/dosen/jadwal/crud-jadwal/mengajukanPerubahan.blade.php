<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\dosen\jadwal\crud-jadwal\mengajukanPerubahan.blade.php -->

<div class="mb-4">
    <h5>Form Mengajukan Perubahan Jadwal Sidang</h5>
</div>

<form>
    <div class="mb-3">
        <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" id="namaMahasiswa" placeholder="Masukkan nama mahasiswa">
    </div>
    <div class="mb-3">
        <label for="judulSidang" class="form-label">Judul Sidang</label>
        <input type="text" class="form-control" id="judulSidang" placeholder="Masukkan judul sidang">
    </div>
    <div class="mb-3">
        <label for="jadwalLama" class="form-label">Jadwal Lama</label>
        <input type="datetime-local" class="form-control" id="jadwalLama">
    </div>
    <div class="mb-3">
        <label for="waktuLama" class="form-label">Waktu Lama</label>
        <input type="time" class="form-control" id="waktuLama">
    </div>
    <div class="mb-3">
        <label for="gedungLama" class="form-label">Gedung Lama</label>
        <input type="text" class="form-control" id="gedungLama" placeholder="Masukkan gedung lama">
    </div>
    <div class="mb-3">
        <label for="jadwalBaru" class="form-label">Jadwal Baru</label>
        <input type="datetime-local" class="form-control" id="jadwalBaru">
    </div>
    <div class="mb-3">
        <label for="waktuBaru" class="form-label">Waktu Baru</label>
        <input type="time" class="form-control" id="waktuBaru">
    </div>
    <div class="mb-3">
        <label for="gedungBaru" class="form-label">Gedung Baru</label>
        <input type="text" class="form-control" id="gedungBaru" placeholder="Masukkan gedung baru">
    </div>
    <div class="mb-3">
        <label for="alasan" class="form-label">Alasan Perubahan</label>
        <textarea class="form-control" id="alasan" rows="3" placeholder="Masukkan alasan perubahan jadwal"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
    <button type="reset" class="btn btn-secondary">Batal</button>
</form>