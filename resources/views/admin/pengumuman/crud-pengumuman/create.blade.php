<div class="row justify-content-center">

    <h1 class="text-center mb-4 fw-bold text-primary">Buat Pengumuman</h1>

    <form action="{{ route('pengumuman.create') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Judul Pengumuman -->
        <div class="mb-3">
            <label for="judul" class="form-label fw-semibold">Judul Pengumuman</label>
            <input type="text" class="form-control shadow-sm rounded-3" id="judul" name="judul"
                placeholder="Contoh: Perubahan Jadwal Sidang" required>
        </div>

        <!-- Isi Pengumuman -->
        <div class="mb-3">
            <label for="isi" class="form-label fw-semibold">Isi Pengumuman</label>
            <textarea class="form-control shadow-sm rounded-3" id="isi" name="isi" rows="6"
                placeholder="Tulis isi lengkap pengumuman di sini..." required></textarea>
        </div>

        <!-- Audiens -->
        <div class="mb-3">
            <label for="audiens" class="form-label fw-semibold">Pilih Audiens</label>
            <select class="form-select shadow-sm rounded-3" id="audiens" name="audiens" required>
                <option value="" disabled selected>-- Pilih Audiens --</option>
                <option value="registered_users">Pengguna Terdaftar</option>
                <option value="dosen">Dosen</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="guest">Tamu</option>
                <option value="all_users">Semua Pengguna</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Submit Pengumuman
            </button>
        </div>
    </form>
</div>
