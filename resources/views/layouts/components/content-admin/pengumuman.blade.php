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
                    <i class="fa fa-spinner fa-spin me-2"></i>Memuat ulang...
                </div>

                <!-- List -->
                <ul class="list-unstyled mb-0" id="announcementList">
                    <li class="mb-3 pb-3 border-bottom d-flex align-items-start">
                        <div class="flex-grow-1" style="font-size: 0.95rem; line-height: 1.6;">
                            <strong class="text-primary">[01/04/2025]</strong> - Pendaftaran ulang mahasiswa dibuka
                            hingga 30 April 2025.
                        </div>
                    </li>
                    <li class="mb-3 pb-3 border-bottom d-flex align-items-start">
                        <div class="flex-grow-1" style="font-size: 0.95rem; line-height: 1.6;">
                            <strong class="text-primary">[02/04/2025]</strong> - Pelatihan penggunaan sistem e-learning
                            akan dilaksanakan minggu depan.
                        </div>
                    </li>
                    <!-- Tambahkan pengumuman lainnya dengan struktur serupa -->
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
                // Bisa tambahkan logika fetch data baru di sini
            }, 1000);
        });

        // Edit / Hapus tombol
        announcementList.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete')) {
                const item = e.target.closest('li');
                if (confirm('Yakin ingin menghapus pengumuman ini?')) {
                    item.remove();
                }
            }

            if (e.target.closest('.btn-edit')) {
                const item = e.target.closest('li');
                const content = item.querySelector('div.flex-grow-1');
                const oldText = content.innerText;
                const newText = prompt('Edit isi pengumuman:', oldText);
                if (newText) {
                    content.innerHTML = newText;
                }
            }
        });
    });
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
