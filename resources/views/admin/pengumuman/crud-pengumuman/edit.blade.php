<div class="row justify-content-center">

    <h1 class="text-center mb-4 fw-bold text-primary">Edit Tawaran Topik</h1>

    <form action="{{ route('pengumuman.create') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama Topik -->
        <div class="mb-3">
            <label for="topik" class="form-label fw-semibold">Judul Topik</label>
            <input type="text" class="form-control shadow-sm rounded-3" id="topik" name="topik"
                placeholder="Contoh: Pengembangan Aplikasi Mobile" value="Pengembangan Aplikasi Mobile" required>
        </div>

        <!-- Deskripsi Topik -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi Topik</label>
            <textarea class="form-control shadow-sm rounded-3" id="deskripsi" name="deskripsi" rows="6"
                placeholder="Jelaskan ruang lingkup, tujuan, dan kebutuhan dari topik ini..." required></textarea>
        </div>

        <!-- Dosen Penawar -->
        <div class="mb-3">
            <label for="dosen" class="form-label fw-semibold">Dosen Pengampu</label>
            <input type="text" class="form-control shadow-sm rounded-3" id="dosen" name="dosen"
                placeholder="Contoh: Dr. Ahmad Basuki, S.Kom., M.T." required>
        </div>

        <!-- Kuota Mahasiswa -->
        <div class="mb-3">
            <label for="kuota" class="form-label fw-semibold">Kuota Mahasiswa</label>
            <input type="number" class="form-control shadow-sm rounded-3" id="kuota" name="kuota"
                placeholder="Contoh: 2" min="1" required>
        </div>

        <!-- Target Audiens -->
        <div class="mb-3">
            <label for="audiens" class="form-label fw-semibold">Pilih Audiens</label>
            <select class="form-select shadow-sm rounded-3" id="audiens" name="audiens" required>
                <option value="" disabled selected>-- Pilih Audiens --</option>
                <option value="pengguna_terdaftar">Pengguna Terdaftar</option>
                <option value="guest">Tamu</option>
                <option value="semua_pengguna">Semua Pengguna</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Submit Topik
            </button>
        </div>
    </form>
</div>
