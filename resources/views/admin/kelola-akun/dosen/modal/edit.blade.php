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
                        {{-- ID dibuat statis dan value dikosongkan --}}
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

                    {{-- 
                        Dropdown Role.
                        Pastikan tombol edit Anda memiliki atribut 'data-role-id'
                        Contoh: data-role-id="{{ $dosen->user->roles->where('nama_role', '!=', 'dosen')->first()->id ?? '' }}"
                    --}}
                    <div class="form-floating mb-3">
                        <select class="form-select" id="edit_role_id" name="role_id">
                            <option value="">Default (Hanya Dosen)</option>
                            @foreach ($roles as $role)
                                {{-- Kondisi if dihapus, JavaScript yang akan menangani pemilihan --}}
                                <option value="{{ $role->id }}">{{ $role->deskripsi }}</option>
                            @endforeach
                        </select>
                        <label for="edit_role_id">Jabatan Tambahan (Opsional)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password"
                            placeholder="Kosongkan jika tidak ingin ganti password">
                        <label>Password Baru (Opsional)</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Ulangi password baru">
                        <label>Konfirmasi Password Baru</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
