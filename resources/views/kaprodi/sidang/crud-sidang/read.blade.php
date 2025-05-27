@extends('layouts.template.kaprodi')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Hasil Nilai Sidang Skripsi</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Tanggal Sidang</th>
                            <th>Nilai Pembimbing</th>
                            <th>Nilai Penguji 1</th>
                            <th>Nilai Penguji 2</th>
                            <th>Nilai Akhir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwalSidangs as $jadwal)
                            @php
                                $sidang = $jadwal->sidang;
                                $tugasAkhir = $sidang->tugasAkhir ?? null;
                                $mahasiswa = $tugasAkhir->mahasiswa ?? null;
                                $judulSkripsi = $tugasAkhir->judul ?? 'N/A';
                                $nilaiPembimbing = $sidang->nilai->where('aspek', 'pembimbing')->first();
                                $nilaiPenguji1 = $sidang->nilai->where('aspek', 'penguji1')->first();
                                $nilaiPenguji2 = $sidang->nilai->where('aspek', 'penguji2')->first();

                                $skorPembimbing = $nilaiPembimbing->skor ?? 0;
                                $skorPenguji1 = $nilaiPenguji1->skor ?? 0;
                                $skorPenguji2 = $nilaiPenguji2->skor ?? 0;

                                $nilaiAkhir = ($skorPembimbing + $skorPenguji1 + $skorPenguji2) / 3;
                                $hasil = $nilaiAkhir >= 70 ? 'Lulus' : 'Tidak Lulus';
                                $statusClass = $hasil === 'Lulus' ? 'badge bg-success' : 'badge bg-danger';
                            @endphp
                            <tr>
                                <td><strong>{{ $mahasiswa->nama ?? 'N/A' }}</strong></td>
                                <td>{{ $judulSkripsi }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                <td><span class="badge bg-primary">{{ $skorPembimbing }}</span></td>
                                <td><span class="badge bg-info">{{ $skorPenguji1 }}</span></td>
                                <td><span class="badge bg-info">{{ $skorPenguji2 }}</span></td>
                                <td><span class="badge bg-warning text-dark">{{ number_format($nilaiAkhir, 2) }}</span></td>
                                <td><span class="{{ $statusClass }}">{{ $hasil }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection