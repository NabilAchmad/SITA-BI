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
                    @forelse ($logs as $log)
                        @php
                            $icon = 'fas fa-info-circle text-muted';
                            $time = \Carbon\Carbon::parse($log->created_at);
                            $timeDiff = $time->diffForHumans();
                            $shortTime = $time->format('H:i') . ' WIB';

                            // Mapping jenis icon berdasarkan action
                            switch (strtolower($log->action)) {
                                case 'buat proposal':
                                case 'buat pengajuan':
                                    $icon = 'fas fa-user-plus text-success';
                                    break;
                                case 'bimbingan disetujui':
                                    $icon = 'fas fa-comments text-primary';
                                    break;
                                case 'unggah dokumen':
                                case 'unggah revisi':
                                    $icon = 'fas fa-file-upload text-info';
                                    break;
                                case 'jadwal sidang':
                                    $icon = 'fas fa-calendar-check text-warning';
                                    break;
                                case 'bimbingan ditolak':
                                    $icon = 'fas fa-exclamation-circle text-danger';
                                    break;
                                default:
                                    $icon = 'fas fa-history text-muted';
                            }
                        @endphp

                        <li class="list-group-item py-3" data-time="{{ $log->created_at }}">
                            <i class="{{ $icon }} me-3 fs-5"></i>
                            {{ $log->deskripsi }}
                            <small class="text-muted">{{ $timeDiff }}</small>
                        </li>
                    @empty
                        <li class="list-group-item py-3 text-muted">Tidak ada aktivitas tercatat.</li>
                    @endforelse
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
