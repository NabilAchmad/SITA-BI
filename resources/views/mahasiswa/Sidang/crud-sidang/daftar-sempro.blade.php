@if (session('alert'))
    <div class="alert alert-{{ session('alert')['type'] }} alert-dismissible fade show" role="alert">
        <strong>{{ session('alert')['title'] }}</strong> {{ session('alert')['message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h1>Form Pendaftaran Seminar Proposal</h1>

<form action="{{ route('mahasiswa.sempro.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
        <input type="text" id="nama_mahasiswa" class="form-control"
            value="{{ $mahasiswa->user->name ?? 'Data tidak ditemukan' }}" readonly>
    </div>

    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" id="nim" class="form-control"
            value="{{ $mahasiswa->nim ?? 'Data tidak ditemukan' }}" readonly>
    </div>

    <div class="mb-3">
        <label for="judul_proposal" class="form-label">Judul Proposal</label>
        <input type="text" id="judul_proposal" name="judul_proposal" class="form-control"
            placeholder="Contoh: Sistem Informasi Seminar Proposal" value="{{ old('judul_proposal') }}" required>

        @error('judul_proposal')
            <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary" type="submit">Simpan Draft Proposal</button>
</form>
