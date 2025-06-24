@extends('layouts.template.kaprodi')

@section('title', 'Jadwal Sidang Akhir')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Sidang Akhir</h1>
        </div>
        <div class="card-body">
            @if($jadwalAkhir->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Nama Mahasiswa</th>
                                <th class="text-center">NIM</th>
                                <th class="text-center">Judul Tugas Akhir</th>
                                <th class="text-center">Tanggal Sidang</th>
                                <th class="text-center">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalAkhir as $jadwal)
                            <tr>
                                <td>{{ $jadwal->sidang->tugasAkhir->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $jadwal->sidang->tugasAkhir->mahasiswa->nim ?? 'N/A' }}</td>
                                <td>{{ $jadwal->sidang->tugasAkhir->judul ?? 'N/A' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}</td>
                                <td class="text-center">{{ $jadwal->ruangan->nama ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $jadwalAkhir->links() }}
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada jadwal sidang akhir saat ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection