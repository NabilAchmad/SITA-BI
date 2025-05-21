<div class="container">
    <h1 class="mb-4">Laporan dan Statistik</h1>

    <div class="mb-5">
        <h4>Jumlah Mahasiswa per Prodi</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Prodi</th>
                    <th>Total Mahasiswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswaPerProdi as $item)
                    <tr>
                        <td>{{ $item->prodi }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <h4>Status Sidang Mahasiswa</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Status Sidang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sidangPerStatus as $item)
                    <tr>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
