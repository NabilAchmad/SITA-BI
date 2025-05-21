<div class="row justify-content-center">

    <h1 class="text-center mb-4 fw-bold text-primary">Edit Pengumuman</h1>

    {{-- Tampilkan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Method spoofing untuk PUT -->

        <!-- Judul Pengumuman -->
        <div class="mb-3">
            <label for="judul" class="form-label fw-semibold">Judul Pengumuman</label>
            <input type="text" class="form-control shadow-sm rounded-3" id="judul" name="judul"
                placeholder="Contoh: Perubahan Jadwal Sidang" required
                value="{{ old('judul', $pengumuman->judul) }}">
        </div>

        <!-- Isi Pengumuman -->
        <div class="mb-3">
            <label for="isi" class="form-label fw-semibold">Isi Pengumuman</label>
            <textarea class="form-control shadow-sm rounded-3" id="isi" name="isi" rows="6"
                placeholder="Tulis isi lengkap pengumuman di sini..." required>{{ old('isi', $pengumuman->isi) }}</textarea>
        </div>

        <!-- Audiens -->
        <div class="mb-3">
            <label for="audiens" class="form-label fw-semibold">Pilih Audiens</label>
            <select class="form-select shadow-sm rounded-3" id="audiens" name="audiens" required>
                <option value="" disabled>-- Pilih Audiens --</option>
                <option value="registered_users" {{ $pengumuman->audiens == 'registered_users' ? 'selected' : '' }}>Pengguna Terdaftar</option>
                <option value="guest" {{ $pengumuman->audiens == 'guest' ? 'selected' : '' }}>Tamu</option>
                <option value="all_users" {{ $pengumuman->audiens == 'all_users' ? 'selected' : '' }}>Semua Pengguna</option>
                <option value="dosen" {{ $pengumuman->audiens == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ $pengumuman->audiens == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('pengumuman.read') }}" class="btn btn-secondary rounded-3 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Update Pengumuman
            </button>
        </div>
    </form>
</div>
