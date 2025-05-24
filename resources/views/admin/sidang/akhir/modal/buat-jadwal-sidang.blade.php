<div class="modal-header">
    <h5 class="modal-title">Isi Jadwal Sidang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('jadwal-sidang.store') }}" method="POST">
        @csrf
        <input type="hidden" name="sidang_id" value="{{ $sidang->id }}">

        <!-- FORM KONTEN SESUAI BLADE YANG KAMU KIRIM -->
        <input type="hidden" name="sidang_id" value="{{ $sidang->id }}">

        <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $sidang->tugasAkhir->mahasiswa->user->name }}"
                readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">NIM</label>
            <input type="text" class="form-control" value="{{ $sidang->tugasAkhir->mahasiswa->nim }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul Skripsi</label>
            <input type="text" class="form-control" value="{{ $sidang->tugasAkhir->judul }}" readonly>
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

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
    </form>
</div>