<form action="" method="POST" class="mx-auto" style="max-width: 700px;">
    @csrf

    <h4 class="mb-3 text-center fw-bold">Form Berita Acara Pra Sidang</h4>

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

    {{-- Jumlah Penguji --}}
    <div class="mb-3">
        <label for="jumlah_penguji" class="form-label fw-semibold">Jumlah Dosen Penguji</label>
        <input type="number" class="form-control" id="jumlah_penguji" name="jumlah_penguji" min="1"
            max="10" placeholder="Masukkan jumlah penguji" required>
    </div>

    {{-- Container dinamis untuk input penguji --}}
    <div id="penguji-container" class="mb-3"></div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary px-4">Simpan Berita Acara</button>
    </div>
</form>

<script>
    document.getElementById('jumlah_penguji').addEventListener('input', function() {
        const jumlah = parseInt(this.value);
        const container = document.getElementById('penguji-container');
        container.innerHTML = '';

        if (!isNaN(jumlah) && jumlah > 0 && jumlah <= 10) {
            for (let i = 1; i <= jumlah; i++) {
                const div = document.createElement('div');
                div.classList.add('mb-2');

                div.innerHTML = `
                    <label class="form-label fw-semibold">Nama Dosen Penguji ${i}</label>
                    <input type="text" name="dosen_penguji_${i}" class="form-control" placeholder="Masukkan nama dosen penguji ${i}" required>
                `;
                container.appendChild(div);
            }
        }
    });
</script>
