<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-danger"><i class="bi bi-calendar-x me-2"></i> Belum Punya Jadwal Sidang Akhir</h1>
            <p class="text-muted mb-0">Daftar mahasiswa yang telah terdaftar sidang akhir, namun belum memiliki jadwal.
            </p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Belum Dijadwalkan</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul TA</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $index => $mhs)
                            @php
                                $sidang = $mhs->tugasAkhir->sidang->firstWhere('status', 'dijadwalkan');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $mhs->user->name }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->tugasAkhir->judul ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($sidang)
                                        <button type="button" class="btn btn-sm btn-success btn-jadwalkan"
                                            data-sidang-id="{{ $sidang->id }}" data-nama="{{ $mhs->user->name }}"
                                            data-nim="{{ $mhs->nim }}" data-judul="{{ $mhs->tugasAkhir->judul }}"
                                            data-url="{{ route('jadwal-sidang.simpanPenguji', ['sidang_id' => $sidang->id]) }}">
                                            <i class="bi bi-calendar-plus me-1"></i> Jadwalkan
                                        </button>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada sidang</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i> Tidak ada mahasiswa yang menunggu
                                    penjadwalan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal container untuk modal dinamis -->
<div id="modalContainer"></div>

<!-- Template Modal Penguji -->
<template id="template-modal-penguji">
    <form method="POST" id="form-penguji">
        @csrf
        <div class="modal fade" tabindex="-1" role="dialog" id="modalPenguji">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">Pilih Dosen Penguji</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto; min-width: 700px;">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search-dosen"
                                placeholder="Cari nama dosen...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover rounded overflow-hidden">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Dosen</th>
                                        <th style="width: 150px;">NIDN</th>
                                        <th style="width: 100px;">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="tbody-dosen">
                                    @foreach ($dosen as $index => $item)
                                        <tr class="dosen-item align-middle">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="nama-dosen">{{ $item->user->name }}</td>
                                            <td>{{ $item->nidn }}</td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input fs-5" type="checkbox"
                                                        name="penguji[]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted fst-italic">* Maksimal pilih 4 dosen penguji</span>
                            <div>
                                <button type="button" class="btn btn-secondary me-2" id="batal-penguji">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btn-simpan-penguji">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
