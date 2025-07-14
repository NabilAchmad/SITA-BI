@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Persetujuan Sidang Akhir Mahasiswa</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($sidangs->count() > 0)
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-white">
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul Tugas Akhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sidangs as $index => $sidang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sidang->tugasAkhir->mahasiswa->user->name ?? '-' }}</td>
                        <td>{{ $sidang->tugasAkhir->judul ?? '-' }}</td>
                        <td>{{ ucfirst($sidang->status) }}</td>
                        <td>
                            <form action="{{ route('dosen.sidang.approvals.approve', $sidang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui sidang ini?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">Tidak ada pendaftaran sidang akhir yang menunggu persetujuan.</div>
    @endif
</div>
@endsection
