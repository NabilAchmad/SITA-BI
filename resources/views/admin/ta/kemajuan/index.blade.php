@extends('layouts.template.main')

@section('title', 'Laporan Kemajuan Tugas Akhir')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Laporan Kemajuan Tugas Akhir</h3>

    @if ($kemajuan->isEmpty())
        <div class="alert alert-warning">Belum ada data kemajuan.</div>
    @else
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kemajuan as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
