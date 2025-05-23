@extends('layouts.template.kaprodi')

@section('content')
<div class="container mt-4">
    <h2>Hasil Nilai Sidang</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Tanggal Sidang</th>
                <th>Nilai Pembimbing</th>
                <th>Nilai Penguji 1</th>
                <th>Nilai Penguji 2</th>
                <th>Nilai Akhir</th>
                <th>Hasil</th>
            </tr>
        </thead>
        <tbody>
            {{-- Contoh data statis, ganti dengan data dinamis dari controller --}}
            <tr>
                <td>Muhammad Abdhu Syukra</td>
                <td>2024-06-01</td>
                <td>85</td>
                <td>80</td>
                <td>82</td>
                <td>82.3</td>
                <td>Lulus</td>
            </tr>
            <tr>
                <td>Joko Winarto</td>
                <td>2024-06-02</td>
                <td>75</td>
                <td>70</td>
                <td>72</td>
                <td>72.3</td>
                <td>Tidak Lulus</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
