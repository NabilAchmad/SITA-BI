@extends('layouts.template.kaprodi')

@section('title', 'Pasca Sidang Sempro')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Pasca Sidang Sempro</h3>
        </div>
        <div class="card-body">
            @if($pascaSempro->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Nama Mahasiswa</th>
                                <th class="text-center">NIM</th>
                                <th class="text-center">Judul Tugas Akhir</th>
                                <th class="text-center">Tanggal Sidang</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pascaSempro as $sidang)
                            <tr>
                                <td>{{ $sidang->tugasAkhir->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $sidang->tugasAkhir->mahasiswa->nim ?? 'N/A' }}</td>
                                <td>{{ $sidang->tugasAkhir->judul ?? 'N/A' }}</td>
                                <td class="text-center">{{ $sidang->tanggal_sidang ?? 'N/A' }}</td>
                                <td class="text-center"><span class="badge bg-success">{{ $sidang->nilai ?? 'N/A' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $pascaSempro->links() }}
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada data pasca sidang sempro.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection