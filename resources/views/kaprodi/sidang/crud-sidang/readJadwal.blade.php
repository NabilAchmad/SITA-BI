{{-- @extends('layouts.template.kaprodi')

@section('title', 'Jadwal Sidang')

@section('content')
<div class="container mt-4">
    <h2>Jadwal Sidang</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Tanggal</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalList as $jadwal)
                <tr>
                    <td>{{ $jadwal->sidang->tugasAkhir->mahasiswa->user->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                    <td>{{ $jadwal->ruangan->nama ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada jadwal sidang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $jadwalList->links() }}
    </div>
</div>
@endsection --}}
