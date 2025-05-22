@extends('layouts.template.mahasiswa')

@section('content')
<div class="container mt-4">
    <h1>Dashboard Mahasiswa</h1>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Pengumuman Terbaru</h3>
            <ul class="list-group">
                @forelse ($pengumuman as $item)
                    <li class="list-group-item">
                        <strong>{{ $item->judul }}</strong><br>
                        <small>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</small>
                        <p>{{ $item->isi }}</p>
                    </li>
                @empty
                    <li class="list-group-item">Tidak ada pengumuman.</li>
                @endforelse
            </ul>
        </div>

        <div class="col-md-6">
            <h3>Jadwal Sidang</h3>
            @if($jadwal->isEmpty())
                <p>Tidak ada jadwal sidang.</p>
            @else
                <ul class="list-group">
                    @foreach ($jadwal as $jad)
                        <li class="list-group-item">
                            <strong>{{ \Carbon\Carbon::parse($jad->tanggal)->format('d M Y') }}</strong><br>
                            {{ $jad->kegiatan }} - {{ $jad->lokasi }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Judul Tugas Akhir</h3>
            @if($judulTA)
                <p><strong>Judul:</strong> {{ $judulTA->judul }}</p>
                <p><strong>Status:</strong> {{ $judulTA->status }}</p>
                <p><strong>Dosen Pembimbing:</strong> {{ $judulTA->dosen_pembimbing }}</p>
            @else
                <p>Belum ada judul tugas akhir.</p>
            @endif
        </div>

        <div class="col-md-6">
            <h3>Sidang</h3>
            @if($sidang)
                <p><strong>Judul:</strong> {{ $sidang->judul }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sidang->tanggal)->format('d M Y') }}</p>
                <p><strong>Status:</strong> {{ $sidang->status }}</p>
                <p><strong>Nilai:</strong> {{ $sidang->nilai }}</p>
            @else
                <p>Belum ada data sidang.</p>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3>Nilai Sidang</h3>
            @if($nilai->isEmpty())
                <p>Tidak ada nilai sidang.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sidang ID</th>
                            <th>Nilai Angka</th>
                            <th>Nilai Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai as $n)
                            <tr>
                                <td>{{ $n->sidang_id }}</td>
                                <td>{{ $n->nilai_angka }}</td>
                                <td>{{ $n->nilai_huruf }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
