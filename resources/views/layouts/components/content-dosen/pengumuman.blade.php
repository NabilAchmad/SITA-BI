<!-- Pengumuman Card -->
<div class="card shadow rounded-4 border-0" id="pengumumanCard">
    <div class="card-header bg-primary text-white rounded-top-4 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-bullhorn me-2"></i>Pengumuman</h5>
            <div class="card-tools">
                <button class="btn btn-icon btn-primary btn-sm btn-toggle">
                    <span class="fa fa-angle-down"></span>
                </button>
                <button class="btn btn-icon btn-primary btn-sm btn-refresh-card">
                    <span class="fa fa-sync-alt"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body px-4 pb-4 pt-3">
        <div class="announcement-list">
            <div class="announcement-content position-relative" style="max-height: 250px; overflow-y: auto;">
                <!-- Loader -->
                <div class="loading-spinner text-center py-2 d-none text-muted">
                    <i class="fa fa-spinner fa-spin me-2"></i> Memuat ulang...
                </div>

                <!-- List -->
                <ul class="list-unstyled mb-0" id="announcementList">
                    @foreach ($pengumumans as $pengumuman)
                        <li class="mb-4 pb-3 border-bottom d-flex align-items-start">
                            <div class="flex-grow-1">
                                <div class="mb-1 d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $pengumuman->pembuat->name ?? 'Admin' }}
                                    </small>
                                </div>
                                <h6 class="fw-semibold text-primary mb-1" style="font-size: 1.05rem;">
                                    {{ $pengumuman->judul }}
                                </h6>
                                <p class="mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi), 100, '...') }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Script Fungsi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.getElementById('pengumumanCard');
        const toggleBtn = card.querySelector('.btn-toggle .fa');
        const refreshBtn = card.querySelector('.btn-refresh-card');
        const cardBody = card.querySelector('.card-body');
        const contentContainer = card.querySelector('.announcement-content');
        const loader = contentContainer.querySelector('.loading-spinner');
        const announcementList = card.querySelector('#announcementList');

        // Collapse/Expand
        card.querySelector('.btn-toggle').addEventListener('click', function() {
            const isHidden = cardBody.style.display === 'none';
            cardBody.style.display = isHidden ? 'block' : 'none';
            toggleBtn.classList.toggle('fa-angle-down', isHidden);
            toggleBtn.classList.toggle('fa-angle-up', !isHidden);
        });

        // Refresh
        refreshBtn.addEventListener('click', function() {
            loader.classList.remove('d-none');
            announcementList.classList.add('d-none');

            setTimeout(() => {
                loader.classList.add('d-none');
                announcementList.classList.remove('d-none');
                console.log('Data pengumuman berhasil dimuat');
                // Bisa tambahkan logika fetch data baru di sini, misalnya menggunakan fetch() atau Axios
            }, 1000);
        });

        // Check if announcements exist
        if (!announcementList.children.length) {
            const noAnnouncementMessage = document.createElement('p');
            noAnnouncementMessage.className = 'text-center text-muted mt-3';
            noAnnouncementMessage.textContent = 'Tidak ada pengumuman.';
            contentContainer.appendChild(noAnnouncementMessage);
        }
    });
</script>
