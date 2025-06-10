<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\crud-bimbingan\ajukan-jadwal.blade.php -->
@extends('layouts.template.main')
@section('title', 'Daftar Mahasiswa Bimbingan ACC')
@section('content')

<div class="mb-4">
    <h5>Daftar Mahasiswa Bimbingan yang Sudah di ACC</h5>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-2">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Judul Sidang</th>
                        <th>Dosen Pembimbing</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Contoh data statis, ganti dengan @foreach jika dari database --}}
                    <tr>
                        <td>1</td>
                        <td>Ahmad Fauzi</td>
                        <td>123456789</td>
                        <td>Sistem Informasi</td>
                        <td>Sistem Informasi Akademik</td>
                        <td>Dr. Budi Santoso</td>
                        <td>2025-06-10</td>
                        <td>09:00 - 10:00</td>
                        <td>ACC</td>
                        <td>
                            <a href="{{ url('admin/bimbingan/menungguReview?id=1') }}" class="btn btn-warning btn-sm">
                                Edit Jadwal
                            </a>
                        </td>
                    </tr>
                    {{-- Tambahkan baris lain sesuai kebutuhan --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection