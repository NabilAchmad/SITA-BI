<!-- Jadwal Sidang Section -->
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0 font-weight-bold text-primary">Jadwal Sidang Skripsi</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="jadwalTable">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Penguji 1</th>
                            <th>Penguji 2</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwalSidangs as $index => $jadwal)
                            @php
                                $sidang = $jadwal->sidang;
                                $tugasAkhir = $sidang->tugasAkhir ?? null;
                                $mahasiswa = $tugasAkhir->mahasiswa ?? null;
                                $judulSkripsi = $tugasAkhir->judul ?? 'N/A';

                                $penguji1 = $sidang->peranDosenTa->where('peran', 'penguji1')->first();
                                $penguji2 = $sidang->peranDosenTa->where('peran', 'penguji2')->first();

                                $namaPenguji1 = $penguji1 ? $penguji1->dosen->nama : 'N/A';
                                $namaPenguji2 = $penguji2 ? $penguji2->dosen->nama : 'N/A';

                                $waktu = $jadwal->waktu_mulai . ' - ' . $jadwal->waktu_selesai;
                                $ruangan = $jadwal->ruangan->nama ?? 'N/A';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $mahasiswa->nama ?? 'N/A' }}</td>
                                <td><span class="text-primary">{{ $judulSkripsi }}</span></td>
                                <td>{{ $namaPenguji1 }}</td>
                                <td>{{ $namaPenguji2 }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                <td class="text-center">{{ $waktu }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ $ruangan }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add this to your CSS -->
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
    }
</style>

<!-- Add this to your JavaScript -->
<script>
    $(document).ready(function() {
        $('#jadwalTable').DataTable({
            "ordering": true,
            "info": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    });
</script>