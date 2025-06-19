@extends('layouts.template.kaprodi')

@section('title', 'Jadwal Sidang Sempro')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Sidang Sempro</h3>
        </div>
        <div class="card-body">
            @if($jadwalSempro->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle">Nama Mahasiswa</th>
                                <th class="text-center align-middle">NIM</th>
                                <th class="text-center align-middle">Judul Tugas Akhir</th>
                                <th class="text-center align-middle">Tanggal Sidang</th>
                                <th class="text-center align-middle">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalSempro as $jadwal)
                            <tr>
                                <td>{{ $jadwal->sidang->tugasAkhir->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $jadwal->sidang->tugasAkhir->mahasiswa->nim ?? 'N/A' }}</td>
                                <td>{{ $jadwal->sidang->tugasAkhir->judul ?? 'N/A' }}</td>
                                <td class="text-center">{{ $jadwal->tanggal }}</td>
                                <td class="text-center">{{ $jadwal->ruangan->nama ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $jadwalSempro->links() }}
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada jadwal sidang sempro.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection