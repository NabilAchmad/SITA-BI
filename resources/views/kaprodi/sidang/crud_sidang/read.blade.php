@extends('layouts.template.main')

@section('title', 'Daftar Nilai Sidang')

@section('content')
<div class="container mt-4">
    <h2>Daftar Nilai Sidang dari Penguji</h2>
    @if(isset($nilaiSidang) && $nilaiSidang->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penguji</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiSidang as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->penguji ?? 'Nama Penguji' }}</td>
                    <td>{{ $nilai->nilai ?? 'Nilai' }}</td>
                    <td>{{ $nilai->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada nilai sidang yang dimasukkan oleh penguji.</p>
    @endif
</div>
@endsection
