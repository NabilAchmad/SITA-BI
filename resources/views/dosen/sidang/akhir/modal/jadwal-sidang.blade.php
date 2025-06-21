<!-- Template Modal Jadwal Sidang -->
<template id="template-modal-jadwal-sidang">
    <form action="{{ route('jadwal-sidang.store') }}" method="POST" id="form-jadwal-sidang">
        @csrf
        <input type="hidden" name="sidang_id" id="jadwal-sidang_id" value="">
        <div class="modal fade" tabindex="-1" role="dialog" id="modalJadwalSidang">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Isi Jadwal Sidang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="jadwal-nama" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" id="jadwal-nim" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Skripsi</label>
                            <input type="text" class="form-control" id="jadwal-judul" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Sidang</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ruangan_id" class="form-label">Ruangan Sidang</label>
                            <select name="ruangan_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach ($ruanganList as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
