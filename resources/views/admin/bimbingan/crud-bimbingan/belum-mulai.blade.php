@extends('layouts.template.main')
@section('title', 'Bimbingan Belum Memulai')
@section('content')
<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\belum-mulai.blade.php -->

<div class="mb-4">
    <h5>Daftar Mahasiswa yang Belum Mulai Bimbingan</h5>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered mt-2">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Mahasiswa</th>
                <th scope="col">NIM</th>
                <th scope="col">Program Studi</th>
                <th scope="col">Judul</th>
                <th scope="col">Status Bimbingan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ahmad Fauzi</td>
                <td>123456789</td>
                <td>Sistem Informasi</td>
                <td>Sistem Informasi Akademik</td>
                <td><span class="badge bg-danger">Belum Mulai</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>987654321</td>
                <td>Teknik Informatika</td>
                <td>Analisis Data Penjualan</td>
                <td><span class="badge bg-danger">Belum Mulai</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Rizky Hidayat</td>
                <td>192837465</td>
                <td>Sistem Informasi</td>
                <td>Pengembangan Aplikasi Mobile</td>
                <td><span class="badge bg-danger">Belum Mulai</span></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection