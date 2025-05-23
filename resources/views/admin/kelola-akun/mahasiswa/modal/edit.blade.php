<!-- Modal Edit Mahasiswa -->
<div class="modal fade" id="editAkunMahasiswaModal" tabindex="-1" aria-labelledby="editAkunMahasiswaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formEditMahasiswa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editAkunMahasiswaModalLabel">Edit Akun Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="edit_nama_mahasiswa" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_nama_mahasiswa" name="name"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="col-md-6">
                        <label for="edit_email_mahasiswa" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email_mahasiswa" name="email"
                            placeholder="Masukkan email">
                    </div>

                    <div class="col-md-6">
                        <label for="edit_nim_mahasiswa" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="edit_nim_mahasiswa" name="nim"
                            placeholder="Masukkan NIM">
                    </div>

                    <div class="col-md-6">
                        <label for="edit_prodi_mahasiswa" class="form-label">PRODI</label>
                        <input type="text" class="form-control" id="edit_prodi_mahasiswa" name="prodi"
                            placeholder="Masukkan PRODI">
                    </div>

                    <div class="col-md-6">
                        <label for="edit_password_mahasiswa" class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" id="edit_password_mahasiswa" name="password"
                            placeholder="Masukkan password baru">
                    </div>

                    <div class="col-md-6">
                        <label for="edit_password_confirmation_mahasiswa" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation_mahasiswa"
                            name="password_confirmation" placeholder="Konfirmasi password baru">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
