<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\sidang\akhir\penilaian\form.blade.php -->
@extends('layouts.template.main')
@section('title', 'Form Penilaian Sidang Akhir')
@section('content')

<div class="mb-4">
    <h5>Form Penilaian Sidang Akhir</h5>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('penilaian.sidang.simpan', $sidang->id) }}">
            @csrf
            <div class="mb-3">
                <label for="aspek" class="form-label">Aspek Penilaian</label>
                <input type="text" class="form-control" id="aspek" name="aspek" required>
            </div>
            <div class="mb-3">
                <label for="skor" class="form-label">Skor</label>
                <input type="number" class="form-control" id="skor" name="skor" min="0" max="100" required>
            </div>
            <div class="mb-3">
                <label for="komentar" class="form-label">Komentar</label>
                <textarea class="form-control" id="komentar" name="komentar"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan Nilai</button>
            <a href="{{ route('penilaian.sidang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection