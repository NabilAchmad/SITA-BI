<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="text-center mb-4">Buat Pengumuman</h1>
            <form action="{{ route('pengumuman.page') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengumuman</label>
                    <input type="text" class="form-control" id="judul" name="judul"
                        placeholder="Contoh: Perubahan Jadwal Sidang" required>
                </div>
                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Pengumuman</label>
                    <textarea class="form-control" id="isi" name="isi" rows="6"
                        placeholder="Tulis isi lengkap pengumuman di sini..." required></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Submit Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>