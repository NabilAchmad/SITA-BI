@extends('layouts.template.kaprodi')

@section('title', 'Edit Nilai Sidang')

@section('content')
<div class="container py-4">
    <h1>Edit Nilai Sidang</h1>

    <form action="{{ route('kaprodi.nilai.update', $nilai->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="mahasiswa" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="mahasiswa" value="{{ $nilai->mahasiswa->nama ?? '' }}" disabled>
        </div>

        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai Tugas Akhir</label>
            <input type="number" class="form-control" id="nilai" name="nilai" value="{{ old('nilai', $nilai->nilai) }}" min="0" max="100" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Menunggu" {{ $nilai->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Disetujui" {{ $nilai->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ $nilai->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kaprodi.nilai.page') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
