@extends('layouts.template.main')
@section('title', 'Data Pasca Sidang')
@section('content')
    <h1 class="mb-4 fw-bold text-primary">Data Pasca Sidang</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Judul Tugas Akhir</th>
                    <th>Tanggal Sidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sidangSelesai as $index => $item)
                    @php
                        $sidang = $item->sidang;
                        $ta = $sidang->tugasAkhir ?? null;
                        $mhs = $ta?->mahasiswa;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $mhs?->user?->name ?? '-' }}</td>
                        <td>{{ $mhs->nim ?? '-' }}</td>
                        <td>{{ $ta->judul ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary btn-sm">
                                Cetak
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada sidang yang selesai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
