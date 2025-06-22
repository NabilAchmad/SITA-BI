@extends('layouts.template.kaprodi')

@section('title', 'Daftar Bimbingan')

@section('content')
<div class="container py-4">
    <h1>Daftar Bimbingan</h1>
    <a href="{{ route('kaprodi.bimbingan.create') }}" class="btn btn-primary mb-3">Tambah Bimbingan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Topik</th>
                <th>Catatan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bimbingans as $bimbingan)
                <tr>
                    <td>{{ $bimbingan->mahasiswa->nama ?? 'N/A' }}</td>
                    <td>{{ $bimbingan->topik }}</td>
                    <td>{{ $bimbingan->catatan }}</td>
                    <td>{{ $bimbingan->tanggal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bimbingans->links() }}
</div>
@endsection
