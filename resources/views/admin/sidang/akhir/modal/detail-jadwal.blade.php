@extends('layouts.template.main')
@section('title', 'Detail Jadwal Sidang')

@push('styles')
    {{-- CSS Kustom untuk Animasi Kartu Flip --}}
    <style>
        .card-container {
            perspective: 1500px;
            max-width: 900px;
            margin: auto;
        }

        .card-flip {
            position: relative;
            /* Krusial untuk positioning anak-anaknya */
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        /* Kedua sisi kartu menggunakan position: absolute agar menumpuk di tempat yang sama */
        .card-front,
        .card-back {
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            /* Sembunyikan sisi belakang saat tidak terlihat */
            position: absolute;
            top: 0;
            left: 0;
        }

        /* Sisi depan (normal) */
        .card-front {
            z-index: 2;
        }

        /* Sisi belakang (awal-awal diputar dan disembunyikan) */
        .card-back {
            transform: rotateY(180deg);
            z-index: 1;
        }

        /* Gaya untuk tampilan info agar lebih rapi */
        .info-group-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.95rem;
        }

        /* [PERBAIKAN] Tambahkan CSS untuk scrolling pada card-body */
        .card-front .card-body,
        .card-back .card-body {
            /* Tentukan tinggi maksimal, contoh 70% dari tinggi viewport */
            max-height: 70vh;
            /* Tambahkan scrollbar vertikal jika konten melebihi max-height */
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    @php
        // Alias variabel untuk kode yang lebih bersih dan aman
        $sidang = $jadwal->sidang ?? null;
        $tugasAkhir = $sidang->tugasAkhir ?? null;
        $mahasiswa = $tugasAkhir->mahasiswa ?? null;
        $userMahasiswa = $mahasiswa->user ?? null;

        $status_kelulusan = $sidang->status_kelulusan ?? 'belum_ditentukan';
        $status_options = [
            'lulus' => ['class' => 'btn-success', 'text' => 'Lulus'],
            'lulus_dengan_revisi' => ['class' => 'btn-info', 'text' => 'Lulus dg. Revisi'],
            'tidak_lulus' => ['class' => 'btn-danger', 'text' => 'Tidak Lulus'],
            'belum_ditentukan' => ['class' => 'btn-secondary', 'text' => 'Belum Ditentukan'],
        ];
        $current_status = $status_options[$status_kelulusan] ?? $status_options['belum_ditentukan'];
    @endphp

    <div class="card-container" style="perspective: 1500px; max-width: 900px; margin: auto;">
        {{-- Wrapper ini adalah elemen yang akan diputar oleh JavaScript --}}
        <div id="cardFlip" class="card-flip"
            style="transition: transform 0.8s; transform-style: preserve-3d; position: relative;">

            <div class="card-front">
                <div class="card shadow-sm rounded-4 border-light mb-5"
                    style="backface-visibility: hidden; position: relative; z-index: 2;">
                    <div class="card-header bg-primary text-white fw-semibold fs-5 py-3 rounded-top-4">
                        <i class="bi bi-calendar-event me-2"></i> Detail Jadwal Sidang
                    </div>
                    <div class="card-body p-lg-5 p-4">
                        {{-- Konten Detail --}}
                        <div class="row g-5">
                            <div class="col-md-5 text-center">
                                <img src="{{ $userMahasiswa && $userMahasiswa->photo ? asset('storage/' . $userMahasiswa->photo) : asset('assets/img/default-avatar.png') }}"
                                    alt="Foto Mahasiswa" class="img-fluid rounded-circle shadow-sm mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                <h5 class="fw-bold text-dark mt-2 mb-1">{{ $userMahasiswa->name ?? 'Nama Mahasiswa' }}</h5>
                                <p class="text-muted fs-6">{{ $mahasiswa->nim ?? '-' }}</p>
                            </div>
                            <div class="col-md-7">
                                <div class="info-group-title">INFORMASI SIDANG</div>
                                <div class="info-item">
                                    <span class="label">Judul TA</span>
                                    <span class="value text-wrap">{{ $tugasAkhir->judul ?? '-' }}</span>
                                </div>
                                <div class="info-group-title">JADWAL & LOKASI</div>
                                <div class="info-item">
                                    <span class="label">Tanggal</span>
                                    <span class="value"
                                        id="tanggalDisplay">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Waktu</span>
                                    <span class="value"
                                        id="waktuDisplay">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Ruangan</span>
                                    <span class="value" id="ruanganDisplay">{{ $jadwal->ruangan->lokasi ?? '-' }}</span>
                                </div>
                                <div class="info-group-title">TIM PENGUJI</div>
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="info-item">
                                        <span class="label">Penguji {{ $i }}</span>
                                        <span class="value"
                                            id="penguji{{ $i }}Display">{{ optional($tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji' . $i))->dosen->user->name ?? '-' }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="border-top mt-4 pt-3 d-flex flex-wrap justify-content-end align-items-center gap-2">
                            <div class="btn-group">
                                <button type="button" class="btn {{ $current_status['class'] }} btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-award-fill me-1"></i> Status: {{ $current_status['text'] }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item"
                                            onclick="submitStatus('{{ $sidang->id ?? '' }}', 'lulus')">Lulus</button></li>
                                    <li><button class="dropdown-item"
                                            onclick="submitStatus('{{ $sidang->id ?? '' }}', 'lulus_dengan_revisi')">Lulus
                                            dengan Revisi</button></li>
                                    <li><button class="dropdown-item"
                                            onclick="submitStatus('{{ $sidang->id ?? '' }}', 'tidak_lulus')">Tidak
                                            Lulus</button></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><button class="dropdown-item text-muted"
                                            onclick="submitStatus('{{ $sidang->id ?? '' }}', 'belum_ditentukan')">Batalkan
                                            Status</button></li>
                                </ul>
                            </div>
                            <button id="btnEdit" type="button" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit Jadwal
                            </button>
                            <a href="{{ route('jurusan.penjadwalan-sidang.index') }}" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-back">
                <div class="card shadow-sm rounded-4 border-light mb-5">
                    <div class="card-header bg-warning text-dark fw-semibold fs-5 py-3 rounded-top-4">
                        <i class="bi bi-pencil-square me-2"></i> Edit Jadwal Sidang
                    </div>
                    <div class="card-body p-lg-5 p-4">
                        <form id="editJadwalForm" method="POST" action="{{ route('jurusan.penjadwalan-sidang.update', $jadwal->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tanggalSidang" class="form-label fw-semibold">Tanggal Sidang</label>
                                    <input type="date" name="tanggalSidang" class="form-control"
                                        value="{{ old('tanggalSidang', $jadwal->tanggal) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="waktuMulai" class="form-label fw-semibold">Waktu Mulai</label>
                                    <input type="time" name="waktuMulai" class="form-control"
                                        value="{{ old('waktuMulai', \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')) }}"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label for="waktuSelesai" class="form-label fw-semibold">Waktu Selesai</label>
                                    <input type="time" name="waktuSelesai" class="form-control"
                                        value="{{ old('waktuSelesai', \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')) }}"
                                        required>
                                </div>
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Penguji {{ $i }}</label>
                                        <select name="penguji[]" class="form-select penguji-select">
                                            <option value="">-- Kosongkan jika tidak ada --</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}"
                                                    {{ optional($tugasAkhir->peranDosenTa->firstWhere('peran', 'penguji' . $i))->dosen_id == $dosen->id ? 'selected' : '' }}>
                                                    {{ $dosen->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endfor
                                <div class="col-md-6">
                                    <label for="ruangan" class="form-label fw-semibold">Ruangan</label>
                                    <select name="ruangan" class="form-select" required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach ($ruangans as $ruangan)
                                            <option value="{{ $ruangan->id }}"
                                                {{ $jadwal->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                                {{ $ruangan->lokasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="border-top mt-4 pt-3 d-flex justify-content-end gap-2">
                                <button type="button" id="btnCancelEdit"
                                    class="btn btn-outline-secondary">Batal</button>
                                <button type="button" id="btnSave" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Form tersembunyi untuk mengirim status kelulusan --}}
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="status" id="statusInput">
    </form>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk mengirim status kelulusan
        function submitStatus(sidangId, status) {
            if (!sidangId) {
                swal("Aksi Gagal", "ID Sidang tidak ditemukan.", "error");
                return;
            }
            const form = document.getElementById('statusForm');
            form.action = `/admin/sidang/akhir/tandai-sidang/${sidangId}`;
            document.getElementById('statusInput').value = status;
            swal({
                title: 'Konfirmasi Perubahan Status',
                text: `Anda yakin ingin mengubah status sidang ini?`,
                icon: 'warning',
                buttons: ["Batal", "Ya, Lanjutkan"],
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    form.submit();
                }
            });
        }

        // Script utama untuk fungsionalitas halaman
        document.addEventListener('DOMContentLoaded', function() {
            const cardFlip = document.querySelector('.card-flip');
            const btnEdit = document.getElementById('btnEdit');
            const btnCancelEdit = document.getElementById('btnCancelEdit');
            const btnSave = document.getElementById('btnSave');
            const editForm = document.getElementById('editJadwalForm');

            btnEdit.addEventListener('click', () => cardFlip.style.transform = 'rotateY(180deg)');
            btnCancelEdit.addEventListener('click', () => cardFlip.style.transform = 'rotateY(0deg)');

            function validateExaminers() {
                const pengujiSelects = document.querySelectorAll('.penguji-select');
                const selectedValues = Array.from(pengujiSelects).map(s => s.value).filter(v => v);

                if (new Set(selectedValues).size !== selectedValues.length) {
                    swal("Validasi Gagal", "Tidak boleh memilih dosen penguji yang sama.", "error");
                    return false;
                }
                if (selectedValues.length < 2) {
                    swal("Validasi Gagal", "Harus memilih minimal 2 dosen penguji.", "error");
                    return false;
                }
                return true;
            }

            btnSave.addEventListener('click', () => {
                if (!editForm.checkValidity()) {
                    editForm.reportValidity();
                    return;
                }
                if (!validateExaminers()) {
                    return;
                }

                const formData = new FormData(editForm);
                fetch(editForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        const responseData = await res.json();
                        if (!res.ok) throw new Error(responseData.message ||
                            'Terjadi kesalahan pada server.');
                        return responseData;
                    })
                    .then(response => {
                        // [PERBAIKAN] Tambahkan console.log untuk debugging
                        console.log("Data diterima dari server:", response);

                        swal("Berhasil", response.message, "success");

                        // [PERBAIKAN] Gunakan kode yang lebih defensif saat membaca respons
                        if (response && response.jadwal) {
                            const jadwal = response.jadwal;
                            const penguji = response.penguji || {};

                            // Update tampilan di sisi depan kartu
                            document.getElementById('tanggalDisplay').textContent = new Date(jadwal
                                .tanggal).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });
                            document.getElementById('waktuDisplay').textContent = jadwal.waktu_mulai
                                .substring(0, 5) + ' - ' + jadwal.waktu_selesai.substring(0, 5);

                            // Pengecekan aman untuk ruangan
                            const ruanganText = (jadwal.ruangan && jadwal.ruangan.lokasi) ? jadwal
                                .ruangan.lokasi : '-';
                            document.getElementById('ruanganDisplay').textContent = ruanganText;

                            for (let i = 1; i <= 4; i++) {
                                document.getElementById(`penguji${i}Display`).textContent = penguji[
                                    `penguji${i}`] || '-';
                            }

                            cardFlip.style.transform = 'rotateY(0deg)';
                        } else {
                            // Jika struktur respons tidak sesuai, beri tahu pengguna untuk refresh
                            swal("Info",
                                "Data berhasil disimpan, namun tampilan belum diperbarui. Silakan refresh halaman.",
                                "info");
                        }
                    })
                    .catch(error => {
                        console.error("Error saat fetch:", error);
                        swal("Gagal", "Gagal menyimpan data: " + error.message, "error");
                    });
            });
        });
    </script>
@endpush
