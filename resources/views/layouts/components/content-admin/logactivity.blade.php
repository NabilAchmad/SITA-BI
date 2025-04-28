<style>
    .activity-scroll {
        max-height: 375px;
        overflow-y: auto;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-right: 120px;
        transition: background-color 0.2s ease-in-out;
    }

    .list-group-item:hover {
        background-color: #f9f9f9;
    }

    .list-group-item small {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }

    .card-title {
        font-weight: 600;
    }
</style>

<div class="col-md-8">
    <div class="card card-round">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-title fs-4">Aktivitas Sistem</div>
                <select id="sortOrder" class="form-select form-select-sm" style="width: auto;">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="chart-container activity-scroll" style="font-size: 1.05rem;">
                <ul id="activityList" class="list-group list-group-flush">
                    <li class="list-group-item py-3" data-time="2025-04-25T08:43:00">
                        <i class="fas fa-user-plus text-success me-3 fs-5"></i>
                        12 Mahasiswa mengajukan proposal hari ini.
                        <small class="text-muted">08:43 WIB</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-25T09:10:00">
                        <i class="fas fa-comments text-primary me-3 fs-5"></i>
                        Dosen Bimbingan menyetujui bimbingan dari Riko.
                        <small class="text-muted">09:10 WIB</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-24T08:00:00">
                        <i class="fas fa-calendar-check text-warning me-3 fs-5"></i>
                        5 Sidang dijadwalkan minggu ini.
                        <small class="text-muted">Kemarin</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-23T14:00:00">
                        <i class="fas fa-file-upload text-info me-3 fs-5"></i>
                        7 Mahasiswa mengunggah revisi laporan.
                        <small class="text-muted">2 hari lalu</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-22T10:00:00">
                        <i class="fas fa-exclamation-circle text-danger me-3 fs-5"></i>
                        3 Dosen belum menyetujui bimbingan.
                        <small class="text-muted">3 hari lalu</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-22T10:00:00">
                        <i class="fas fa-exclamation-circle text-danger me-3 fs-5"></i>
                        3 Dosen belum menyetujui bimbingan.
                        <small class="text-muted">3 hari lalu</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-24T08:00:00">
                        <i class="fas fa-calendar-check text-warning me-3 fs-5"></i>
                        5 Sidang dijadwalkan minggu ini.
                        <small class="text-muted">Kemarin</small>
                    </li>
                    <li class="list-group-item py-3" data-time="2025-04-23T14:00:00">
                        <i class="fas fa-file-upload text-info me-3 fs-5"></i>
                        7 Mahasiswa mengunggah revisi laporan.
                        <small class="text-muted">2 hari lalu</small>
                    </li>
                    <!-- Tambah item lainnya jika perlu -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sortOrder').addEventListener('change', function() {
        const sortOrder = this.value;
        const list = document.getElementById('activityList');
        const items = Array.from(list.querySelectorAll('.list-group-item'));

        items.sort((a, b) => {
            const timeA = new Date(a.getAttribute('data-time'));
            const timeB = new Date(b.getAttribute('data-time'));
            return sortOrder === 'asc' ? timeA - timeB : timeB - timeA;
        });

        // Clear and re-append in sorted order
        list.innerHTML = '';
        items.forEach(item => list.appendChild(item));
    });
</script>
