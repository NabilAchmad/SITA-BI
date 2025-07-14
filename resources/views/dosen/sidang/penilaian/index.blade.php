<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\sidang\akhir\penilaian\index.blade.php -->
@extends('layouts.template.main')
@section('title', 'Penilaian Sidang Akhir')
@section('content')

<div class="mb-4">
    <h5>Daftar Penilaian Sidang Akhir</h5>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-2">
                <thead class="table-light">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Topik</th>
                        <th>Rata-rata Skor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sidangs as $sidang)
                    <tr>
                        <td>{{ $sidang->mahasiswa->nama }}</td>
                        <td>{{ $sidang->mahasiswa->nim }}</td>
                        <td>{{ $sidang->topik }}</td>
                        <td>
                            @if($sidang->rataRataNilai())
                                {{ number_format($sidang->rataRataNilai(), 2) }}
                            @else
                                <em>Belum dinilai</em>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $sidang->statusKelulusan() === 'Lulus' ? 'bg-success' : 'bg-danger' }}">
                                {{ $sidang->statusKelulusan() }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('penilaian.sidang.form', $sidang->id) }}" class="btn btn-primary btn-sm">Beri Nilai</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection