
    <div class="card-header text-center text-primary">
        <h1>Progress Tugas Akhir</h1>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark"   >
                <tr>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Presentase Progress</th>
                    <th>Action</th>
                </tr>
            </thead>
<tbody>
    @forelse ($tugasAkhir as $ta)
        <tr>
            <td>{{ $ta->judul }}</td>
            <td>
                @if ($ta->status === 'diajukan')
                    Dalam Proses
                @elseif ($ta->status === 'disetujui')
                    Disetujui
                @elseif ($ta->status === 'ditolak')
                    Ditolak
                @elseif ($ta->status === 'selesai')
                    Selesai
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($ta->tanggal_pengajuan)->format('Y-m-d') }}</td>
            <td>
                @php
                    // Simulasi progress dummy berdasarkan status
                    $progress = match($ta->status) {
                        'diajukan' => 25,
                        'disetujui' => 50,
                        'selesai' => 100,
                        default => 0
                    };
                @endphp
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $progress }}%
                    </div>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger" {{ $ta->status !== 'diajukan' ? 'disabled' : '' }}>Batalkan</button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">Belum ada pengajuan tugas akhir.</td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>