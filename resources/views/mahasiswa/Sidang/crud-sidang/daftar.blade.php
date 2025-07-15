<h1>Form Pendaftaran Sidang Tugas Akhir</h1>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('mahasiswa.sidang.store-akhir') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Nama Mahasiswa</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->user->name ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label>NIM</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->nim ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label>Judul Tugas Akhir</label>
        <input type="text" name="judul_ta" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Dosen Pembimbing 1</label>
        <input type="text" class="form-control"
            value="{{ $mahasiswa->tugasAkhir && $mahasiswa->tugasAkhir->pembimbingSatu ? $mahasiswa->tugasAkhir->pembimbingSatu->dosen->user->name : '-' }}"
            readonly>
    </div>

    <div class="mb-3">
        <label>Dosen Pembimbing 2</label>
        <input type="text" class="form-control"
            value="{{ $mahasiswa->tugasAkhir && $mahasiswa->tugasAkhir->pembimbingDua ? $mahasiswa->tugasAkhir->pembimbingDua->dosen->user->name : '-' }}"
            readonly>
    </div>


    <div class="mb-3">
        <label>Jumlah Bimbingan</label>
        <input type="number" name="jumlah_bimbingan" class="form-control" min="0" required>
    </div>

    <div class="mb-3">
        <label>Upload File Tugas Akhir</label>
        <input type="file" name="file_ta" class="form-control" required>
    </div>

    <button class="btn btn-primary" type="submit">Daftar</button>
</form>
