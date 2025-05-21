<h1 class="mb-4">Tambah Akun Dosen</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('akun-dosen.store') }}" method="POST">
    @csrf
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama dosen" required>
        <label for="nama">Nama</label>
    </div>

    <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email dosen" required>
        <label for="email">Email</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Masukkan NIDN" required>
        <label for="nidn">NIDN</label>
    </div>

    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
        <label for="password">Password</label>
    </div>

    <div class="form-floating mb-4">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
            placeholder="Ulangi password" required>
        <label for="password_confirmation">Konfirmasi Password</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Tambah Akun Dosen</button>
</form>