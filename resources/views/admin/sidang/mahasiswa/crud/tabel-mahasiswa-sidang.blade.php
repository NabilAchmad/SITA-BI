@extends('layouts.template.main')

@section('title', 'Mahasiswa Akan Sidang (Belum Dijadwalkan)')

@section('content')
    <div class="container">
        <h1 class="mb-4 fw-bold text-primary">Mahasiswa Akan Sidang (Belum Dijadwalkan)</h1>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Judul Skripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswa as $index => $mhs)
                    @php
                        $sidang = $jadwal->sidang;
                        $ta = $sidang->tugasAkhir ?? null;
                        $mahasiswa = $ta?->mahasiswa;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mahasiswa?->user?->name ?? '-' }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $ta?->judul ?? '-' }}</td>
                        <td>
                            @if ($sidang)
                                <a href="{{ route('jadwal-sidang.create', ['sidang_id' => $sidang->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    Tentukan Jadwal
                                </a>
                            @else
                                <span class="text-muted">Belum daftar sidang</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada mahasiswa yang menunggu penjadwalan
                            sidang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
