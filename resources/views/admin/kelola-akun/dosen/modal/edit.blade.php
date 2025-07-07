<!-- Modal Edit Akun Dosen -->
<div class="modal fade" id="editAkunModal" tabindex="-1" aria-labelledby="editAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editAkunModalLabel">Edit Akun Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Form action akan diisi secara dinamis oleh JavaScript --}}
                <form action="" method="POST" id="formEditAkun">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editNama" name="nama"
                            placeholder="Masukkan nama dosen" value="" required>
                        <label for="editNama">Nama</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="editEmail" name="email"
                            placeholder="Masukkan email dosen" value="" required>
                        <label for="editEmail">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editNidn" name="nidn"
                            placeholder="Masukkan NIDN" value="" required>
                        <label for="editNidn">NIDN</label>
                    </div>

                    {{-- ✅ PERBAIKAN: Dropdown Role sekarang mengirim 'role_name' --}}
                    <div class="form-floating mb-3">
                        <select class="form-select" id="edit_role_name" name="role_name">
                            <option value="">Default (Hanya Dosen)</option>
                            @foreach ($roles as $role)
                                {{-- JavaScript yang akan menangani role yang terpilih --}}
                                <option value="{{ $role->name }}">
                                    {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        <label for="edit_role_name">Jabatan Tambahan (Opsional)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password"
                            placeholder="Kosongkan jika tidak ingin ganti password" autocomplete="new-password">
                        <label>Password Baru (Opsional)</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Ulangi password baru" autocomplete="new-password">
                        <label>Konfirmasi Password Baru</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
