@extends('layouts.template.kaprodi')

@section('content')
<div class="container mt-4">
    <h2>Tambah Nilai Sidang</h2>
    <form method="POST" action="{{ route('kaprodi.nilai.create') }}">
        @csrf
        <div class="mb-3">
            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" placeholder="Masukkan nama mahasiswa" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_sidang" class="form-label">Tanggal Sidang</label>
            <input type="date" class="form-control" id="tanggal_sidang" name="tanggal_sidang" required>
        </div>
        <div class="mb-3">
            <label for="nilai_pembimbing" class="form-label">Nilai Pembimbing</label>
            <input type="number" class="form-control" id="nilai_pembimbing" name="nilai_pembimbing" min="0" max="100" required>
        </div>
        <div class="mb-3">
            <label for="nilai_penguji_1" class="form-label">Nilai Penguji 1</label>
            <input type="number" class="form-control" id="nilai_penguji_1" name="nilai_penguji_1" min="0" max="100" required>
        </div>
        <div class="mb-3">
            <label for="nilai_penguji_2" class="form-label">Nilai Penguji 2</label>
            <input type="number" class="form-control" id="nilai_penguji_2" name="nilai_penguji_2" min="0" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
    </form>
</div>
@endsection
