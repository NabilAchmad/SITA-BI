<!-- Modal Edit Akun Dosen -->
<div class="modal fade" id="editAkunModal" tabindex="-1" aria-labelledby="editAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="editAkunModalLabel">Edit Akun Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditAkun">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama" id="editNama" required>
                        <label for="editNama">Nama</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="editEmail" required>
                        <label for="editEmail">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nidn" id="editNidn" required>
                        <label for="editNidn">NIDN</label>
                    </div>

                    {{-- Dropdown Role --}}
                    <div class="form-floating mb-3">
                        <select class="form-select" id="role_id" name="role_id">
                            <option value="" selected>Default Dosen</option>
                            @foreach ($roles as $role)
                                @if (in_array($role->nama_role, ['kaprodi', 'kajur']))
                                    <option value="{{ $role->id }}">{{ ucfirst($role->nama_role) }} -
                                        {{ $role->deskripsi }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="role_id">Jabatan (Opsional)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password"
                            placeholder="Kosongkan jika tidak ingin ganti">
                        <label>Password (opsional)</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Ulangi password">
                        <label>Konfirmasi Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
