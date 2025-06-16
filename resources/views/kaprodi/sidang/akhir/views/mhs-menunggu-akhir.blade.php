@extends('layouts.template.kaprodi')

@section('title', 'Mahasiswa Menunggu Sidang Akhir')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-users fa-2x me-3"></i>
                <h1 class="h3 mb-0">Mahasiswa Menunggu Sidang Akhir</h1>
            </div>
        </div>
        <div class="card-body p-4">
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button" role="tab" aria-controls="semua" aria-selected="true">
                        <i class="fas fa-list me-2"></i>Semua
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="d3-tab" data-bs-toggle="tab" data-bs-target="#d3" type="button" role="tab" aria-controls="d3" aria-selected="false">
                        <i class="fas fa-graduation-cap me-2"></i>D3
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="d4-tab" data-bs-toggle="tab" data-bs-target="#d4" type="button" role="tab" aria-controls="d4" aria-selected="false">
                        <i class="fas fa-graduation-cap me-2"></i>D4
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
                    @if($mahasiswaMenunggu->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th class="align-middle"><i class="fas fa-user me-2"></i>Nama</th>
                                        <th class="align-middle"><i class="fas fa-id-card me-2"></i>NIM</th>
                                        <th class="align-middle"><i class="fas fa-graduation-cap me-2"></i>Program</th>
                                        <th class="align-middle"><i class="fas fa-book me-2"></i>Judul Tugas Akhir</th>
                                        <th class="align-middle"><i class="fas fa-info-circle me-2"></i>Status Sidang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaMenunggu as $mhs)
                                    <tr>
                                        <td class="align-middle">{{ $mhs->user->name ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">{{ $mhs->nim }}</td>
                                        <td class="align-middle text-center">{{ $mhs->program }}</td>
                                        <td class="align-middle">{{ $mhs->tugasAkhir->judul ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Menunggu Penjadwalan
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $mahasiswaMenunggu->links() }}
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                Tidak ada mahasiswa yang menunggu penjadwalan sidang akhir.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="d3" role="tabpanel" aria-labelledby="d3-tab">
                    @if($mahasiswaMenunggu->where('program', 'D3')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th class="align-middle"><i class="fas fa-user me-2"></i>Nama</th>
                                        <th class="align-middle"><i class="fas fa-id-card me-2"></i>NIM</th>
                                        <th class="align-middle"><i class="fas fa-book me-2"></i>Judul Tugas Akhir</th>
                                        <th class="align-middle"><i class="fas fa-info-circle me-2"></i>Status Sidang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaMenunggu->where('program', 'D3') as $mhs)
                                    <tr>
                                        <td class="align-middle">{{ $mhs->user->name ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">{{ $mhs->nim }}</td>
                                        <td class="align-middle">{{ $mhs->tugasAkhir->judul ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Menunggu Penjadwalan
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                Tidak ada mahasiswa D3 yang menunggu penjadwalan sidang akhir.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="d4" role="tabpanel" aria-labelledby="d4-tab">
                    @if($mahasiswaMenunggu->where('program', 'D4')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th class="align-middle"><i class="fas fa-user me-2"></i>Nama</th>
                                        <th class="align-middle"><i class="fas fa-id-card me-2"></i>NIM</th>
                                        <th class="align-middle"><i class="fas fa-book me-2"></i>Judul Tugas Akhir</th>
                                        <th class="align-middle"><i class="fas fa-info-circle me-2"></i>Status Sidang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaMenunggu->where('program', 'D4') as $mhs)
                                    <tr>
                                        <td class="align-middle">{{ $mhs->user->name ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">{{ $mhs->nim }}</td>
                                        <td class="align-middle">{{ $mhs->tugasAkhir->judul ?? 'N/A' }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Menunggu Penjadwalan
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                Tidak ada mahasiswa D4 yang menunggu penjadwalan sidang akhir.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection