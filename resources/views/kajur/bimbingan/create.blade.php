@extends('layouts.template.kajur')

@section('title', 'Tambah Bimbingan')

@section('content')
<div class="container py-4">
    <h1>Tambah Bimbingan</h1>

    <form action="{{ route('kajur.bimbingan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="mahasiswa_id" class="form-label">Nama Mahasiswa</label>
            <select class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>
                <option value="">Pilih Mahasiswa</option>
                @foreach(\App\Models\Mahasiswa::all() as $mahasiswa)
                    <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="topik" class="form-label">Topik</label>
            <input type="text" class="form-control" id="topik" name="topik" required>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kajur.bimbingan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
