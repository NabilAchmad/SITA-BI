<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\crud-bimbingan\lihat-bimbingan.blade.php -->
@extends('layouts.template.main')
@section('title', 'Lihat Jadwal Bimbingan')
@section('content')

<div class="mb-4">
    <h5>Daftar Jadwal Bimbingan</h5>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered mt-2">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Mahasiswa</th>
                <th scope="col">NIM</th>
                <th scope="col">Program Studi</th>
                <th scope="col">Judul Sidang</th>
                <th scope="col">Dosen Pembimbing</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
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
                <td>Dr. Budi Santoso</td>
                <td>2025-06-10</td>
                <td>09:00 - 10:00</td>
                <td>
                    <button class="btn btn-success btn-sm">ACC</button>
                    <button class="btn btn-danger btn-sm">Tolak</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>987654321</td>
                <td>Teknik Informatika</td>
                <td>Analisis Data Penjualan</td>
                <td>Dr. Rina Dewi</td>
                <td>2025-06-11</td>
                <td>10:00 - 11:00</td>
                <td>
                    <button class="btn btn-success btn-sm">ACC</button>
                    <button class="btn btn-danger btn-sm">Tolak</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Rizky Hidayat</td>
                <td>192837465</td>
                <td>Sistem Informasi</td>
                <td>Pengembangan Aplikasi Mobile</td>
                <td>Dr. Andi Wijaya</td>
                <td>2025-06-12</td>
                <td>13:00 - 14:00</td>
                <td>
                    <button class="btn btn-success btn-sm">ACC</button>
                    <button class="btn btn-danger btn-sm">Tolak</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection