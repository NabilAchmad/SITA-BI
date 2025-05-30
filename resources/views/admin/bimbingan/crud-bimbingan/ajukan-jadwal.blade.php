<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\crud-bimbingan\ajukan-jadwal.blade.php -->
@extends('layouts.template.main')
@section('title', 'Ajukan Jadwal Bimbingan')
@section('content')

<div class="mb-4">
    <h5>Form Mengajukan Jadwal Bimbingan</h5>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Bimbingan</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="waktu" class="form-label">Waktu Bimbingan</label>
                <input type="time" class="form-control" id="waktu" name="waktu" required>
            </div>
            <div class="mb-3">
                <label for="topik" class="form-label">Topik Bimbingan</label>
                <input type="text" class="form-control" id="topik" name="topik" placeholder="Masukkan topik bimbingan" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Jadwal</button>
            <button type="reset" class="btn btn-secondary">Batal</button>
        </form>
    </div>
</div>
@endsection