<div class="col-12">
    <div class="card card-round rounded-4 shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">Riwayat Pengajuan Tugas Akhir</div>
            <div class="dropdown">
                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Export PDF</a>
                    <a class="dropdown-item" href="#">Filter</a>
                    <a class="dropdown-item" href="#">Lihat Semua</a>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-3 pt-3">
            <div class="input-group mb-3">
                <span class="input-group-text bg-light text-dark border-end-0">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0"
                    placeholder="Cari mahasiswa, NIM, atau judul TA...">
            </div>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light sticky-top bg-white shadow-sm" style="z-index: 1;">
                        <tr>
                            <th style="position: sticky; top: 0; background: white;">Nama Mahasiswa</th>
                            <th class="text-end" style="position: sticky; top: 0; background: white;">NIM</th>
                            <th class="text-end" style="position: sticky; top: 0; background: white;">Judul TA</th>
                            <th class="text-end" style="position: sticky; top: 0; background: white;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatTA as $ta)
                            <tr>
                                <td>{{ $ta->mahasiswa->user->name ?? '-' }}</td>
                                <td class="text-end">{{ $ta->mahasiswa->nim ?? '-' }}</td>
                                <td class="text-end">{{ $ta->judul ?? '-' }}</td>
                                <td class="text-end">
                                    @php
                                        $status = strtolower($ta->status);
                                    @endphp
                                    @if ($status === 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ($status === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif ($status === 'menunggu' || $status === 'menunggu verifikasi')
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pengajuan tugas akhir.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- No Result Message -->
            <div id="noResult" class="text-center py-3 text-muted" style="display: none;">
                <i class="fa fa-exclamation-circle me-2"></i>Data tidak ditemukan.
            </div>
        </div>
    </div>
</div>

<!-- Script Filter (Search Logic + No Result) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('tbody tr');
        const noResult = document.getElementById('noResult');

        searchInput.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                if (rowText.includes(keyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResult.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    });
</script>
