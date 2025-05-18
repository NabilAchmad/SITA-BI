<h1 class="mb-4">Edit Akun Dosen</h1>

<form action="{{ route('akun-dosen.update', $dosen->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="nama" value="{{ old('nama', $dosen->user->name) }}" required>
        <label>Nama</label>
    </div>

    <div class="form-floating mb-3">
        <input type="email" class="form-control" name="email" value="{{ old('email', $dosen->user->email) }}" required>
        <label>Email</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
        <label>NIDN</label>
    </div>

    <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin ganti">
        <label>Password (opsional)</label>
    </div>

    <div class="form-floating mb-4">
        <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password">
        <label>Konfirmasi Password</label>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('akun-dosen.kelola') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
