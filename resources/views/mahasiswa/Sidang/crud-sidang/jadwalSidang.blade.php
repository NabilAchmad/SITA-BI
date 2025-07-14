
<div class="container py-5">
    <h3 class="mb-4">Jadwal Sidang Mahasiswa</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-white">
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Waktu Sidang</th>
                <th>Ruangan</th>
                <th>Dosen Penguji</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sidangs as $index => $sidang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sidang->tugasAkhir->mahasiswa->user->name ?? '-' }}</td>
                    <td>{{ $sidang->tugasAkhir->judul ?? '-' }}</td>
                    <td>{{ $sidang->jadwalSidang ? $sidang->jadwalSidang->tanggal->format('d F Y') . ' ' . $sidang->jadwalSidang->jam : '-' }}</td>
                    <td>{{ $sidang->jadwalSidang->ruangan->nama ?? '-' }}</td>
                    <td>
                        @php
                            $pengujiNames = $sidang->nilaiSidang->pluck('dosen.user.name')->unique();
                        @endphp
                        {{ $pengujiNames->join(', ') }}
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#detail-{{ $sidang->id }}" aria-expanded="false" aria-controls="detail-{{ $sidang->id }}">
                            Detail
                        </button>
                    </td>
                </tr>
                <tr class="collapse" id="detail-{{ $sidang->id }}">
                    <td colspan="7">
                        <div class="card p-3">
                            <h5>Detail Sidang</h5>
                            <p><strong>Nama Mahasiswa:</strong> {{ $sidang->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                            <p><strong>Judul Skripsi:</strong> {{ $sidang->tugasAkhir->judul ?? '-' }}</p>
                            <p><strong>Waktu Sidang:</strong> {{ $sidang->jadwalSidang ? $sidang->jadwalSidang->tanggal->format('d F Y') . ' ' . $sidang->jadwalSidang->jam : '-' }}</p>
                            <p><strong>Ruangan:</strong> {{ $sidang->jadwalSidang->ruangan->nama ?? '-' }}</p>
                            <p><strong>Dosen Penguji:</strong></p>
                            <ul>
                                @foreach($sidang->nilaiSidang as $nilai)
                                    <li>{{ $nilai->dosen->user->name ?? 'Dosen' }} - {{ $nilai->aspek }} (Skor: {{ $nilai->skor }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada jadwal sidang yang diberikan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
