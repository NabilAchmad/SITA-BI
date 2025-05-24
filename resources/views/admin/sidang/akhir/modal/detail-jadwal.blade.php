<div class="card-container" style="perspective: 1500px; max-width: 900px; margin: auto;">

    <!-- Card wrapper untuk animasi -->
    <div class="card-flip" style="transition: transform 0.8s; transform-style: preserve-3d; position: relative;">

        <!-- Detail Card (depan) -->
        <div class="card shadow-sm rounded-4 mb-5 border-0 bg-white"
            style="backface-visibility: hidden; position: relative; z-index: 2;">
            <div class="card-header bg-primary text-white fw-semibold fs-5 py-3 rounded-top-4">
                <i class="bi bi-calendar-event me-2"></i> Detail Jadwal Sidang
            </div>

            <div class="card-body p-4">
                <div class="row g-4 align-items-start">
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('assets/img/team/erland.jpg') }}" alt="Foto Mahasiswa"
                            class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 220px; object-fit: cover;">
                        <div class="fw-semibold text-dark">
                            {{ $jadwal->sidang->tugasAkhir->mahasiswa->user->name ?? '-' }}</div>
                    </div>

                    <div class="col-md-9">
                        <div class="row gx-4">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-secondary text-uppercase fw-bold small mb-3">Biodata Mahasiswa</h6>
                                <ul class="list-unstyled lh-base">
                                    <li class="mb-3"><strong>Nama:</strong>
                                        {{ $jadwal->sidang->tugasAkhir->mahasiswa->user->name ?? '-' }}</li>
                                    <li class="mb-3"><strong>NIM:</strong>
                                        {{ $jadwal->sidang->tugasAkhir->mahasiswa->nim ?? '-' }}</li>
                                    <li class="mb-3"><strong>Email:</strong>
                                        {{ $jadwal->sidang->tugasAkhir->mahasiswa->user->email ?? '-' }}</li>
                                </ul>
                            </div>

                            <div class="col-md-6 mb-4">
                                <h6 class="text-secondary text-uppercase fw-bold small mb-3">Informasi Sidang</h6>
                                <ul class="list-unstyled lh-base">
                                    <li class="mb-3"><strong>Judul TA:</strong>
                                        {{ $jadwal->sidang->tugasAkhir->judul ?? '-' }}</li>
                                    <li class="mb-3"><strong>Penguji 1:</strong>
                                        <span
                                            id="penguji1Display">{{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji1'))->dosen->user->name ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3"><strong>Penguji 2:</strong>
                                        <span
                                            id="penguji2Display">{{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji2'))->dosen->user->name ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3"><strong>Penguji 3:</strong>
                                        <span
                                            id="penguji3Display">{{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji3'))->dosen->user->name ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3"><strong>Penguji 4:</strong>
                                        <span
                                            id="penguji4Display">{{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji4'))->dosen->user->name ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3"><strong>Tanggal Sidang:</strong> <span
                                            id="tanggalDisplay">{{ $jadwal->tanggal }}</span></li>
                                    <li class="mb-3"><strong>Waktu:</strong>
                                        <span
                                            id="waktuDisplay">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</span>
                                    </li>
                                    <li class="mb-3"><strong>Ruangan:</strong>
                                        <span id="ruanganDisplay">{{ $jadwal->ruangan->lokasi ?? '-' }}</span>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <hr class="my-4">
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <button id="btnEdit" type="button" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <form action="{{ route('jadwal-sidang.destroy', $jadwal->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>

                    <form action="{{ route('jadwal-sidang.mark-done', $jadwal->id) }}" method="POST"
                        onsubmit="return confirm('Tandai mahasiswa ini sudah melaksanakan sidang?')" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                        </button>
                    </form>

                    <a href="#" class="btn btn-secondary btn-sm">
                        <i class="bi bi-download me-1"></i> Unduh Berita Acara
                    </a>

                    <a href="{{ route('jadwal.sidang.akhir') }}" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Card (belakang) -->
        <div class="card shadow-sm rounded-4 mb-5 border-0 bg-white"
            style="backface-visibility: hidden; position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotateY(180deg); z-index: 1; padding: 20px;">
            <div class="card-header bg-primary text-white fw-semibold fs-5 py-3 rounded-top-4">
                <i class="bi bi-pencil-square me-2"></i> Edit Jadwal Sidang
            </div>
            <form method="POST" action="{{ route('jadwal-sidang.update', $jadwal->id) }}" id="editJadwalForm">
                @csrf
                @method('PUT')
                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label for="tanggalSidang" class="form-label fw-semibold">Tanggal Sidang</label>
                        <input type="date" id="tanggalSidang" name="tanggalSidang" class="form-control"
                            value="{{ old('tanggalSidang', $jadwal->tanggal) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="waktuMulai" class="form-label fw-semibold">Waktu Mulai</label>
                        <input type="time" id="waktuMulai" name="waktuMulai" class="form-control"
                            value="{{ old('waktuMulai', \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')) }}"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label for="waktuSelesai" class="form-label fw-semibold">Waktu Selesai</label>
                        <input type="time" id="waktuSelesai" name="waktuSelesai" class="form-control"
                            value="{{ old('waktuSelesai', \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')) }}"
                            required>
                    </div>

                    <!-- Pilihan dosen penguji satu per satu -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penguji 1</label>
                        <select name="penguji1" class="form-select" required>
                            <option value="">Pilih Dosen Penguji 1</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji1'))->dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penguji 2</label>
                        <select name="penguji2" class="form-select">
                            <option value="">Pilih Dosen Penguji 2</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji2'))->dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penguji 3</label>
                        <select name="penguji3" class="form-select">
                            <option value="">Pilih Dosen Penguji 3</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji3'))->dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penguji 4</label>
                        <select name="penguji4" class="form-select">
                            <option value="">Pilih Dosen Penguji 4</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ optional($jadwal->sidang->tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji4'))->dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="ruangan" class="form-label fw-semibold">Ruangan</label>
                        <select name="ruangan" id="ruangan" class="form-select" required>
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}"
                                    {{ $jadwal->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                    {{ $ruangan->lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                        <button type="button" id="btnCancelEdit" class="btn btn-outline-secondary">Batal</button>
                        <button type="button" id="btnSave" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/sidang/sidang.js') }}"></script>
@endpush
