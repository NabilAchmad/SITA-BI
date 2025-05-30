<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\crud-bimbingan\ajukan-perubahan.blade.php -->
@extends('layouts.template.main')
@section('title', 'Ajukan Perubahan Jadwal Bimbingan')
@section('content')

<div class="mb-4">
    <h5>Form Mengajukan Perubahan Jadwal Bimbingan</h5>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Baru</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="waktu" class="form-label">Waktu Baru</label>
                <input type="time" class="form-control" id="waktu" name="waktu" required>
            </div>
            <div class="mb-3">
                <label for="alasan" class="form-label">Alasan Perubahan</label>
                <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Masukkan alasan perubahan jadwal" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
            <button type="reset" class="btn btn-secondary">Batal</button>
        </form>
    </div>
</div>
@endsection