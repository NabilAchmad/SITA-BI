<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- modal lebih besar -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel"><i class="bi bi-person-circle"></i> Detail Akun Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex gap-4 align-items-center">
                <!-- Foto Profil -->
                <div style="min-width: 150px; max-width: 150px;">
                    <img id="detailFoto" src="{{ asset('assets/img/team/nabil.jpg') }}" alt="Foto Profil"
                        class="img-thumbnail"
                        style="width: 150px; height: 200px; object-fit: cover; border-radius: 8px;">
                </div>
                <!-- Detail Info -->
                <div class="flex-grow-1">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 140px;">Nama</th>
                                <td id="detailNama"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="detailEmail"></td>
                            </tr>
                            <tr>
                                <th>NIDN</th>
                                <td id="detailNidn"></td>
                            </tr>
                            <tr>
                                <th>Dibuat</th>
                                <td id="detailCreated"></td>
                            </tr>
                            <tr>
                                <th>Terakhir diubah</th>
                                <td id="detailUpdated"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>