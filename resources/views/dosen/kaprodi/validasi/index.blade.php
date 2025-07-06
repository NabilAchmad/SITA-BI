@extends('layouts.template.main')
@section('title', 'Validasi Judul Tugas Akhir')

@section('content')
    <div class="container-fluid">
        @php
            // Cek peran user yang sedang login untuk menampilkan judul yang sesuai
            $user = auth()->user();
            $isKaprodiD3 = $user->hasRole('kaprodi-d3');
            $isKaprodiD4 = $user->hasRole('kaprodi-d4');
        @endphp

        <h5 class="fw-bold text-primary mb-3">
            <i class="bi bi-journal-check me-2"></i>
            Validasi Pengajuan Judul Tugas Akhir
            {{-- Menambahkan label prodi yang dikelola oleh Kaprodi --}}
            @if ($isKaprodiD3)
                <span class="badge bg-info">Program Studi D3</span>
            @elseif($isKaprodiD4)
                <span class="badge bg-info">Program Studi D4</span>
            @endif
        </h5>

        {{-- Search Form --}}
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        {{-- Tab Status --}}
        <ul class="nav nav-pills nav-fill mb-3" id="statusTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                    type="button" role="tab" aria-controls="menunggu" aria-selected="true">
                    Menunggu Validasi <span class="badge bg-warning ms-1">{{ $tugasAkhirMenunggu->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="diterima-tab" data-bs-toggle="tab" data-bs-target="#diterima" type="button"
                    role="tab" aria-controls="diterima" aria-selected="false">
                    Diterima <span class="badge bg-success ms-1">{{ $tugasAkhirDiterima->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak" type="button"
                    role="tab" aria-controls="ditolak" aria-selected="false">
                    Ditolak <span class="badge bg-danger ms-1">{{ $tugasAkhirDitolak->count() }}</span>
                </button>
            </li>
        </ul>

        {{-- Konten Tab --}}
        <div class="tab-content" id="statusTabContent">
            <div class="tab-pane fade show active" id="menunggu" role="tabpanel" aria-labelledby="menunggu-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirMenunggu])
            </div>
            <div class="tab-pane fade" id="diterima" role="tabpanel" aria-labelledby="diterima-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirDiterima])
            </div>
            <div class="tab-pane fade" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirDitolak])
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetailTA" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Pengajuan Judul</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Data Mahasiswa --}}
                    <table class="table table-borderless table-sm mb-3">
                        <tbody>
                            <tr>
                                <td style="width: 150px;"><strong>Nama Mahasiswa</strong></td>
                                <td style="width: 10px;">:</td>
                                <td><span id="modalNama"></span></td>
                            </tr>
                            <tr>
                                <td><strong>NIM</strong></td>
                                <td>:</td>
                                <td><span id="modalNim"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Program Studi</strong></td>
                                <td>:</td>
                                <td><span id="modalProdi"></span></td>
                            </tr>
                            <tr>
                                <td class="align-top"><strong>Judul Diajukan</strong></td>
                                <td class="align-top">:</td>
                                <td class="align-top"><span id="modalJudul"></span></td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Informasi Approval/Reject (jika sudah divalidasi) --}}
                    <div id="wrapDiterima" class="alert alert-success d-none"></div>
                    <div id="wrapDitolak" class="alert alert-danger d-none"></div>

                    <hr>

                    {{-- Area Pengecekan Kemiripan (Hanya muncul jika bisa divalidasi) --}}
                    <div id="pengecekan-kemiripan-section" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Pengecekan Kemiripan Judul</h6>
                            {{-- Tombol untuk memicu pengecekan --}}
                            <button id="btn-cek-kemiripan" class="btn btn-info btn-sm" type="button">
                                <i class="bi bi-search"></i> Lakukan Pengecekan
                            </button>
                        </div>
                        {{-- Area untuk menampilkan hasil pengecekan --}}
                        <div id="hasil-kemiripan-container" style="display:none;">
                            {{-- Hasil akan dimuat di sini oleh AJAX --}}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <form method="POST" id="formValidasi" action="" class="w-100">
                        @csrf
                        {{-- Tombol Aksi (Hanya muncul jika bisa divalidasi) --}}
                        <div class="d-flex justify-content-end d-none" id="wrapActionButtons">
                            <button type="button" class="btn btn-danger me-2" id="btnTolak">Tolak</button>
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" id="formTolak" action="" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white py-3">
                        <h5 class="modal-title fw-bold" id="modalTolakLabel">
                            <i class="bi bi-x-circle-fill me-2"></i>Tolak Judul Tugas Akhir
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Perhatian!</strong> Penolakan akan dikirimkan kepada mahasiswa beserta alasan yang Anda
                            berikan.
                        </div>

                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label fw-semibold">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control border-2" rows="5"
                                placeholder="Berikan penjelasan detail alasan penolakan..." required minlength="10"></textarea>
                            <div class="form-text">Minimal 10 karakter</div>
                            <div class="invalid-feedback">Harap isi alasan penolakan dengan lengkap (minimal 10 karakter).
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-send-fill me-1"></i> Kirim Penolakan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi komponen modal sekali saja untuk efisiensi
            const modalDetailElement = document.getElementById('modalDetailTA');
            const modalTolakElement = document.getElementById('modalTolak');
            const modalDetailTA = new bootstrap.Modal(modalDetailElement);
            const modalTolakTA = new bootstrap.Modal(modalTolakElement);

            // Menampilkan kembali modal detail setelah modal tolak ditutup
            modalTolakElement.addEventListener('hidden.bs.modal', () => modalDetailTA.show());

            // Menangani notifikasi dari session jika ada
            @if (session('alert'))
                swal({
                    title: "{{ session('alert.title') }}",
                    text: "{{ session('alert.message') }}",
                    icon: "{{ session('alert.type') }}",
                    button: "OK",
                });
            @endif

            // Menggunakan event delegation: satu event listener untuk semua tombol detail
            document.body.addEventListener('click', function(event) {
                const detailButton = event.target.closest('button[data-id]');
                if (detailButton) {
                    const id = detailButton.getAttribute('data-id');
                    showDetail(id);
                }
            });

            // Event listener untuk tombol "Tolak" utama di modal detail
            document.getElementById('btnTolak').addEventListener('click', function() {
                modalDetailTA.hide();
                // Beri jeda agar transisi modal lancar
                setTimeout(() => modalTolakTA.show(), 200);
            });

            /**
             * Fungsi Utama: Mengambil data, mengisi modal, dan menampilkan detail.
             * Menggunakan async/await untuk kode yang lebih bersih.
             * @param {string} id - ID dari Tugas Akhir.
             */
            async function showDetail(id) {
                // 1. Reset UI Modal ke kondisi awal setiap kali dibuka
                resetModalUI();

                try {
                    // 2. Ambil data detail dari server
                    const response = await fetch(`{{ url('/dosen/validasi/detail') }}/${id}`);
                    if (!response.ok) {
                        throw new Error(`Server merespons dengan status: ${response.status}`);
                    }
                    const data = await response.json();

                    // 3. Isi semua detail ke dalam elemen modal
                    fillModalDetails(data, id);

                    // 4. Tambahkan event listener untuk tombol cek kemiripan (jika ada)
                    const btnCek = document.getElementById('btn-cek-kemiripan');
                    if (btnCek) {
                        // Hapus event listener lama dan buat baru untuk menghindari duplikasi
                        btnCek.onclick = () => handleCekKemiripan(id);
                    }

                    // 5. Tampilkan modal
                    modalDetailTA.show();

                } catch (error) {
                    console.error('Gagal memuat detail:', error);
                    swal('Gagal Memuat',
                        'Terjadi kesalahan saat mengambil data dari server. Silakan coba lagi.', 'error');
                }
            }

            /**
             * Menangani logika saat tombol "Lakukan Pengecekan" diklik.
             * @param {string} id - ID dari Tugas Akhir.
             */
            async function handleCekKemiripan(id) {
                const btn = document.getElementById('btn-cek-kemiripan');
                const container = document.getElementById('hasil-kemiripan-container');

                // Update UI ke status "loading"
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status"></span> Mencari...';
                container.style.display = 'block';
                container.innerHTML =
                    '<div class="text-center text-muted p-3">Membandingkan judul dengan ribuan data historis...</div>';

                try {
                    const url = `{{ url('/dosen/validasi') }}/${id}/cek-kemiripan`;
                    const response = await fetch(url);

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Gagal terhubung ke server untuk pengecekan.');
                    }

                    const hasil = await response.json();
                    renderKemiripanResult(hasil); // Tampilkan hasil ke UI
                    btn.style.display = 'none'; // Sembunyikan tombol setelah pengecekan berhasil

                } catch (error) {
                    console.error('Error saat cek kemiripan:', error);
                    container.innerHTML =
                        `<div class="alert alert-danger py-2 small"><strong><i class="bi bi-exclamation-triangle-fill"></i> Gagal:</strong> ${error.message}</div>`;
                    btn.disabled = false; // Aktifkan kembali tombol jika gagal
                    btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Coba Lagi';
                }
            }

            /**
             * Mengisi hasil pengecekan kemiripan ke dalam container.
             * @param {Array} hasil - Array objek hasil dari server.
             */
            function renderKemiripanResult(hasil) {
                const container = document.getElementById('hasil-kemiripan-container');
                let content = '';

                if (hasil && hasil.length > 0) {
                    content +=
                        `<p class="mb-2 small">💡 Ditemukan <strong>${hasil.length} judul</strong> dengan kemiripan signifikan:</p>`;
                    content +=
                        '<div class="table-responsive border rounded" style="max-height: 250px; overflow-y: auto;">';
                    content += '<table class="table table-striped table-bordered table-sm small mb-0">';
                    content +=
                        '<thead class="table-light text-center"><tr><th style="width:15%;">Kemiripan</th><th>Judul</th><th>Mahasiswa</th><th style="width:10%;">Tahun</th></tr></thead>';
                    content += '<tbody>';
                    hasil.forEach(item => {
                        content += `<tr>
                        <td class="text-center align-middle"><span class="badge bg-danger fs-6">${item.persentase}%</span></td>
                        <td>${item.judul}</td>
                        <td class="text-nowrap">${item.nama_mahasiswa}</td>
                        <td class="text-center">${item.tahun_lulus}</td>
                    </tr>`;
                    });
                    content += '</tbody></table></div>';
                } else {
                    content =
                        '<div class="alert alert-success py-2 mt-2">✅ <strong>Aman!</strong> Tidak ditemukan judul yang memiliki kemiripan signifikan.</div>';
                }
                container.innerHTML = content;
            }

            /**
             * Mengisi semua data dari server ke elemen-elemen di modal.
             * @param {object} data - Objek data dari `getDetail`.
             * @param {string} id - ID Tugas Akhir.
             */
            function fillModalDetails(data, id) {
                // Isi detail dasar
                document.getElementById('modalNama').innerText = data.nama || '-';
                document.getElementById('modalNim').innerText = data.nim || '-';
                document.getElementById('modalProdi').innerText = data.prodi || '-';
                document.getElementById('modalJudul').innerText = data.judul || '-';

                // Atur URL untuk form terima dan tolak
                document.getElementById('formValidasi').action = `{{ url('/dosen/validasi/terima') }}/${id}`;
                document.getElementById('formTolak').action = `{{ url('/dosen/validasi/tolak') }}/${id}`;

                // Tampilkan/sembunyikan elemen berdasarkan status `actionable`
                const isActionable = data.actionable;
                document.getElementById('wrapActionButtons').classList.toggle('d-none', !isActionable);
                document.getElementById('pengecekan-kemiripan-section').classList.toggle('d-none', !isActionable);

                // Tampilkan info jika sudah disetujui
                const wrapDiterima = document.getElementById('wrapDiterima');
                wrapDiterima.classList.toggle('d-none', !data.disetujui_oleh);
                if (data.disetujui_oleh) {
                    wrapDiterima.innerHTML =
                        `<strong>Telah Disetujui</strong> oleh <strong>${data.disetujui_oleh}</strong> pada ${data.tanggal_disetujui}.`;
                }

                // Tampilkan info jika sudah ditolak
                const wrapDitolak = document.getElementById('wrapDitolak');
                wrapDitolak.classList.toggle('d-none', !data.alasan_penolakan);
                if (data.alasan_penolakan) {
                    wrapDitolak.innerHTML =
                        `<strong>Telah Ditolak</strong> oleh <strong>${data.ditolak_oleh}</strong> pada ${data.tanggal_ditolak}.<br><strong>Alasan:</strong> ${data.alasan_penolakan}`;
                }
            }

            /**
             * Membersihkan semua konten dinamis di dalam modal.
             */
            function resetModalUI() {
                // Bersihkan hasil pengecekan
                const container = document.getElementById('hasil-kemiripan-container');
                container.style.display = 'none';
                container.innerHTML = '';

                // Reset tombol cek kemiripan
                const btnCek = document.getElementById('btn-cek-kemiripan');
                btnCek.style.display = 'block';
                btnCek.disabled = false;
                btnCek.innerHTML = '<i class="bi bi-search"></i> Lakukan Pengecekan';

                // Kosongkan semua data detail
                document.getElementById('modalNama').innerText = '';
                document.getElementById('modalNim').innerText = '';
                document.getElementById('modalProdi').innerText = '';
                document.getElementById('modalJudul').innerText = '';

                // Sembunyikan semua wrapper info
                document.getElementById('wrapDiterima').classList.add('d-none');
                document.getElementById('wrapDitolak').classList.add('d-none');
            }
        });
    </script>
@endpush
