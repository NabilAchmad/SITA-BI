<div class="modal fade" id="detailAkunModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="detailModalLabel"><i class="bi bi-person-vcard-fill me-2"></i>Detail Akun
                    Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Kolom Foto -->
                    <div class="col-lg-4 text-center mb-4 mb-lg-0">
                        <img id="detailFoto" src="" alt="Foto Profil" class="img-fluid rounded-3 shadow-sm"
                            style="width: 200px; height: 260px; object-fit: cover;"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x260/6c757d/white?text=Foto';">
                    </div>
                    <!-- Kolom Detail Info -->
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-1" id="detailNama"></h3>
                        <p class="text-primary mb-3" id="detailEmail"></p>

                        <div class="mb-3">
                            <small class="text-muted d-block">NIDN</small>
                            <p class="fw-bold fs-5 mb-0" id="detailNidn"></p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Jabatan / Peran</small>
                            <div id="detailRoles" class="d-flex flex-wrap gap-2 mt-1">
                                {{-- Roles akan diisi oleh JavaScript sebagai badges --}}
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Akun Dibuat</small>
                                <span id="detailCreated"></span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Terakhir Diubah</small>
                                <span id="detailUpdated"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangkap event klik pada semua tombol dengan class .btn-detail
            const detailButtons = document.querySelectorAll('.btn-detail');
            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('detailAkunModal');

                    // Mengambil data dari atribut data-* tombol
                    const nama = this.dataset.nama;
                    const email = this.dataset.email;
                    const nidn = this.dataset.nidn;
                    const foto = this.dataset.foto;
                    const created = this.dataset.created;
                    const updated = this.dataset.updated;
                    const roles = JSON.parse(this.dataset.roles || '[]');

                    // Mengisi elemen di dalam modal
                    modal.querySelector('#detailNama').textContent = nama;
                    modal.querySelector('#detailEmail').textContent = email;
                    modal.querySelector('#detailNidn').textContent = nidn;
                    modal.querySelector('#detailFoto').src = foto ? `/storage/public/avatars/${foto}` :
                        'https://placehold.co/200x260/6c757d/white?text=Foto';
                    modal.querySelector('#detailCreated').textContent = new Date(created)
                        .toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    modal.querySelector('#detailUpdated').textContent = new Date(updated)
                        .toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });

                    // Mengisi roles/jabatan
                    const rolesContainer = modal.querySelector('#detailRoles');
                    rolesContainer.innerHTML = ''; // Kosongkan dulu
                    if (roles.length > 0) {
                        roles.forEach(role => {
                            let badgeClass = 'bg-secondary';
                            if (role.nama_role.includes('kaprodi')) badgeClass =
                                'bg-success';
                            if (role.nama_role.includes('kajur')) badgeClass =
                                'bg-info text-dark';
                            if (role.nama_role.includes('dosen')) badgeClass = 'bg-primary';

                            const badge =
                                `<span class="badge ${badgeClass}">${role.deskripsi || role.nama_role}</span>`;
                            rolesContainer.innerHTML += badge;
                        });
                    } else {
                        rolesContainer.innerHTML =
                            '<span class="badge bg-light text-dark">Tidak ada peran</span>';
                    }

                    // Membuka modal
                    const bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                });
            });
        });
    </script>
@endpush
