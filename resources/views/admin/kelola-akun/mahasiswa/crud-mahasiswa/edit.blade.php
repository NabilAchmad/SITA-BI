<h1 class="mb-4">Edit Akun Mahasiswa</h1>

<form action="{{ route('akun-mahasiswa.update', $mahasiswa->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap"
            value="{{ old('name', $mahasiswa->user->name) }}">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email"
            value="{{ old('email', $mahasiswa->user->email) }}">
    </div>

    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM"
            value="{{ old('nim', $mahasiswa->nim) }}">
    </div>

    <div class="mb-3">
        <label for="prodi" class="form-label">PRODI</label>
        <input type="text" class="form-control" id="prodi" name="prodi" placeholder="Masukkan PRODI"
            value="{{ old('prodi', $mahasiswa->prodi) }}">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin ganti)</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
            placeholder="Konfirmasi password baru">
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('akun-mahasiswa.kelola') }}" class="btn btn-secondary">Batal</a>
</form>
