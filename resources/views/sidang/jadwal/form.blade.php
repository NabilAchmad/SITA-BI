<div class="container mt-5">
    <h1 class="mb-4">Form Jadwal Sidang</h1>
    <form>
        <div class="mb-3">
            <label for="namaMahasiswa" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="namaMahasiswa" placeholder="Masukkan nama mahasiswa">
        </div>
        <div class="mb-3">
            <label for="nimMahasiswa" class="form-label">NIM Mahasiswa</label>
            <input type="text" class="form-control" id="nimMahasiswa" placeholder="Masukkan NIM mahasiswa">
        </div>
        <div class="mb-3">
            <label for="judulSkripsi" class="form-label">Judul Skripsi</label>
            <input type="text" class="form-control" id="judulSkripsi" placeholder="Masukkan judul skripsi">
        </div>
        <div class="mb-3">
            <label for="tanggalSidang" class="form-label">Tanggal Sidang</label>
            <input type="date" class="form-control" id="tanggalSidang">
        </div>
        <div class="mb-3">
            <label for="waktuSidang" class="form-label">Waktu Sidang</label>
            <input type="time" class="form-control" id="waktuSidang">
        </div>
        <div class="mb-3">
            <label for="ruanganSidang" class="form-label">Ruangan Sidang</label>
            <input type="text" class="form-control" id="ruanganSidang" placeholder="Masukkan ruangan sidang">
        </div>
        <div class="mb-3">
            <label for="dosenPenguji" class="form-label">Dosen Penguji</label>
            <select class="form-select" id="dosenPenguji">
                <option selected>Pilih dosen penguji</option>
                <option value="1">Dosen 1</option>
                <option value="2">Dosen 2</option>
                <option value="3">Dosen 3</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
