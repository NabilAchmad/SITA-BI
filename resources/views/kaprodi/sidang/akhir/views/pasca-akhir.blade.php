@extends('layouts.template.kaprodi')

@section('title', 'Post-Final Defense')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gradient-primary">
            <h6 class="m-0 font-weight-bold text-dark2">
                <i class="fas fa-graduation-cap me-2"></i>Post-Final Defense Data
            </h6>
        </div>
        <div class="card-body">
            @if($pascaAkhir->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="20%">Student Name</th>
                                <th width="10%">Student ID</th>
                                <th width="35%">Thesis Title</th>
                                <th class="text-center" width="15%">Defense Date</th>
                                <th class="text-center" width="15%">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pascaAkhir as $index => $sidang)
                                @php
                                    $nilaiAkhir = $sidang->nilai->first()->nilai ?? null;
                                    $badge = 'secondary';
                                    if ($nilaiAkhir !== null) {
                                        $badge = $nilaiAkhir >= 80 ? 'success' : ($nilaiAkhir >= 70 ? 'warning' : 'danger');
                                    }
                                @endphp
                                <tr class="align-middle">
                                    <td class="text-center">{{ $index + $pascaAkhir->firstItem() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-graduate text-primary me-2"></i>
                                            {{ $sidang->tugasAkhir->mahasiswa->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>{{ $sidang->tugasAkhir->mahasiswa->nim ?? 'N/A' }}</td>
                                    <td>{{ $sidang->tugasAkhir->judul ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <i class="far fa-calendar-alt text-primary me-1"></i>
                                        {{ Carbon\Carbon::parse($sidang->tanggal_sidang)->format('d F Y') ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $badge }} rounded-pill px-3">
                                            {{ $nilaiAkhir ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    {{ $pascaAkhir->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-gray-400 mb-3"></i>
                    <p class="text-gray-500 mb-0">No post-defense data available.</p>
                    <small class="text-muted">Please wait until data is submitted</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection