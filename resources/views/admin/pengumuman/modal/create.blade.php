<!-- Modal Tambah -->
<div class="modal fade" id="tambahPengumumanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.pengumuman.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pengumuman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi</label>
                        <textarea name="isi" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="audiens" class="form-label">Audiens</label>
                        <select name="audiens" class="form-select" required>
                            <option value="all_users">Semua Pengguna</option>
                            <option value="registered_users">Pengguna Terdaftar</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="guest">Tamu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
