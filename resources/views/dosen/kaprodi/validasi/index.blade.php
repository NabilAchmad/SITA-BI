@extends('layouts.template.main')
@section('title', 'Validasi Judul Tugas Akhir')

@section('content')
    {{-- ... (Seluruh bagian HTML Anda dari atas hingga modal tidak perlu diubah) ... --}}
    <div class="container-fluid py-3">
        @php
            $user = auth()->user();
            $isKaprodiD3 = $user->hasRole('kaprodi-d3');
            $isKaprodiD4 = $user->hasRole('kaprodi-d4');
        @endphp

        {{-- Judul Halaman --}}
        <div class="mb-4">
            <h4 class="fw-semibold text-dark mb-2" style="font-size: 1.3rem;">
                <i class="bi bi-journal-check me-2 text-primary"></i>
                Validasi Pengajuan Judul Tugas Akhir
            </h4>
            @if ($isKaprodiD3)
                <span class="badge bg-soft-info text-dark fw-medium px-3 py-2 rounded-pill shadow-sm border">
                    <i class="bi bi-award me-1"></i> Program Studi D3
                </span>
            @elseif ($isKaprodiD4)
                <span class="badge bg-soft-info text-dark fw-medium px-3 py-2 rounded-pill shadow-sm border">
                    <i class="bi bi-award me-1"></i> Program Studi D4
                </span>
            @endif
        </div>

        {{-- Form Pencarian --}}
        <form method="GET" class="mb-4">
            <div class="input-group input-group-md shadow-sm rounded-pill">
                <span class="input-group-text bg-white border-end-0 ps-4 pe-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 border-end-0 py-2 px-3"
                    placeholder="Cari nama atau NIM mahasiswa..." value="{{ request('search') }}" style="font-size: 1rem;">
                <button type="submit" class="btn btn-outline-primary px-4 rounded-end-pill border-start-0">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </form>

        {{-- Tab Navigasi --}}
        <div class="bg-light border shadow-sm rounded-top px-3 pt-3">
            <ul class="nav nav-tabs border-0" id="statusTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link @if (request()->tab == null || request()->tab == 'menunggu') active @endif fw-semibold text-dark border-0 bg-transparent me-3"
                        id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu" type="button" role="tab"
                        aria-controls="menunggu" aria-selected="true" style="font-size: 1rem;">
                        <i class="bi bi-hourglass-split me-1 text-warning"></i> Menunggu
                        <span class="badge bg-warning-subtle text-dark ms-2">{{ $tugasAkhirMenunggu->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link @if (request()->tab == 'diterima') active @endif fw-semibold text-dark border-0 bg-transparent me-3"
                        id="diterima-tab" data-bs-toggle="tab" data-bs-target="#diterima" type="button" role="tab"
                        aria-controls="diterima" aria-selected="false" style="font-size: 1rem;">
                        <i class="bi bi-check-circle me-1 text-success"></i> Diterima
                        <span class="badge bg-success-subtle text-success ms-2">{{ $tugasAkhirDiterima->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link @if (request()->tab == 'ditolak') active @endif fw-semibold text-dark border-0 bg-transparent"
                        id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak" type="button" role="tab"
                        aria-controls="ditolak" aria-selected="false" style="font-size: 1rem;">
                        <i class="bi bi-x-circle me-1 text-danger"></i> Ditolak
                        <span class="badge bg-danger-subtle text-danger ms-2">{{ $tugasAkhirDitolak->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        {{-- Konten Tab --}}
        <div class="tab-content bg-white shadow-sm rounded-bottom p-3" id="statusTabContent">
            <div class="tab-pane fade show active position-relative" id="menunggu" role="tabpanel"
                aria-labelledby="menunggu-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirMenunggu])
            </div>
            <div class="tab-pane fade position-relative" id="diterima" role="tabpanel" aria-labelledby="diterima-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirDiterima])
            </div>
            <div class="tab-pane fade position-relative" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                @include('dosen.kaprodi.partials.table', ['tugasAkhirCollection' => $tugasAkhirDitolak])
            </div>
        </div>
    </div>


    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetailTA" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-semibold" id="modalDetailLabel">
                        <i class="bi bi-info-circle me-2"></i> Detail Pengajuan Judul
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Data Mahasiswa --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Mahasiswa</h6>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-secondary" style="width: 150px;">Nama Mahasiswa</td>
                                    <td style="width: 10px;">:</td>
                                    <td><span id="modalNama"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">NIM</td>
                                    <td>:</td>
                                    <td><span id="modalNim"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Program Studi</td>
                                    <td>:</td>
                                    <td><span id="modalProdi"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary align-top">Judul Diajukan</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top"><span id="modalJudul"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Informasi Approval/Reject --}}
                    <div id="wrapDiterima" class="alert alert-success d-none rounded shadow-sm py-2 px-3"></div>
                    <div id="wrapDitolak" class="alert alert-danger d-none rounded shadow-sm py-2 px-3"></div>

                    {{-- Area Pengecekan Kemiripan --}}
                    <div id="pengecekan-kemiripan-section" class="d-none mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-primary">
                                <i class="bi bi-search me-2"></i> Pengecekan Kemiripan Judul
                            </h6>
                            <button id="btn-cek-kemiripan" class="btn btn-outline-info btn-sm rounded-pill"
                                type="button">
                                <i class="bi bi-search"></i> Lakukan Pengecekan
                            </button>
                        </div>
                        <div id="hasil-kemiripan-container" style="display: none;">
                            {{-- AJAX load area --}}
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <form method="POST" id="formValidasi" action="" class="w-100">
                        @csrf
                        <div class="d-flex justify-content-end gap-2 d-none" id="wrapActionButtons">
                            <button type="button" class="btn btn-outline-danger" id="btnTolak">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
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
            // ... (kode inisialisasi modal dan event listener lainnya tetap sama) ...
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

            async function showDetail(id) {
                resetModalUI();

                try {
                    // ✅ FINAL: Menggunakan route name yang lengkap dan benar
                    const urlTemplate = "{{ route('jurusan.validasi-judul.detail', ['tugasAkhir' => ':id']) }}";
                    const url = urlTemplate.replace(':id', id);

                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`Server merespons dengan status: ${response.status}`);
                    }
                    const data = await response.json();

                    fillModalDetails(data, id);

                    const btnCek = document.getElementById('btn-cek-kemiripan');
                    if (btnCek) {
                        btnCek.onclick = () => handleCekKemiripan(id);
                    }
                    modalDetailTA.show();
                } catch (error) {
                    console.error('Gagal memuat detail:', error);
                    swal('Gagal Memuat',
                        'Terjadi kesalahan saat mengambil data dari server. Silakan coba lagi.', 'error');
                }
            }

            async function handleCekKemiripan(id) {
                const btn = document.getElementById('btn-cek-kemiripan');
                const container = document.getElementById('hasil-kemiripan-container');

                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status"></span> Mencari...';
                container.style.display = 'block';
                container.innerHTML =
                    '<div class="text-center text-muted p-3">Membandingkan judul dengan ribuan data historis...</div>';

                try {
                    // ✅ FINAL: Menggunakan route name yang lengkap dan benar
                    const urlTemplate =
                        "{{ route('jurusan.validasi-judul.cek-kemiripan', ['tugasAkhir' => ':id']) }}";
                    const url = urlTemplate.replace(':id', id);

                    const response = await fetch(url);
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Gagal terhubung ke server untuk pengecekan.');
                    }
                    const hasil = await response.json();
                    renderKemiripanResult(hasil);
                    btn.style.display = 'none';
                } catch (error) {
                    console.error('Error saat cek kemiripan:', error);
                    container.innerHTML =
                        `<div class="alert alert-danger py-2 small"><strong><i class="bi bi-exclamation-triangle-fill"></i> Gagal:</strong> ${error.message}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Coba Lagi';
                }
            }

            function renderKemiripanResult(hasil) {
                // ... (Fungsi ini tidak perlu diubah) ...
                const container = document.getElementById('hasil-kemiripan-container');
                let content = '';

                if (hasil && hasil.length > 0) {
                    content +=
                        `<p class="mb-2 small"><i class="bi bi-lightbulb-fill text-primary"></i>Ditemukan <strong>${hasil.length} judul</strong> dengan kemiripan signifikan:</p>`;
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

            function fillModalDetails(data, id) {
                document.getElementById('modalNama').innerText = data.nama || '-';
                document.getElementById('modalNim').innerText = data.nim || '-';
                document.getElementById('modalProdi').innerText = data.prodi || '-';
                document.getElementById('modalJudul').innerText = data.judul || '-';

                // ✅ FINAL: Menggunakan route name yang lengkap dan benar untuk form actions
                const terimaUrlTemplate = "{{ route('jurusan.validasi-judul.terima', ['tugasAkhir' => ':id']) }}";
                const tolakUrlTemplate = "{{ route('jurusan.validasi-judul.tolak', ['tugasAkhir' => ':id']) }}";

                document.getElementById('formValidasi').action = terimaUrlTemplate.replace(':id', id);
                document.getElementById('formTolak').action = tolakUrlTemplate.replace(':id', id);

                // ✅ PERBAIKAN: Gunakan flag yang benar untuk setiap elemen

                // 1. Tombol Setujui/Tolak tetap menggunakan 'data.actionable'
                document.getElementById('wrapActionButtons').classList.toggle('d-none', !data.actionable);

                // 2. Bagian Cek Kemiripan sekarang menggunakan flag baru 'data.can_check_similarity'
                document.getElementById('pengecekan-kemiripan-section').classList.toggle('d-none', !data
                    .can_check_similarity);

                const wrapDiterima = document.getElementById('wrapDiterima');
                wrapDiterima.classList.toggle('d-none', !data.disetujui_oleh);
                if (data.disetujui_oleh) {
                    wrapDiterima.innerHTML =
                        `<strong>Telah Disetujui</strong> oleh <strong>${data.disetujui_oleh}</strong> pada ${data.tanggal_disetujui}.`;
                }

                const wrapDitolak = document.getElementById('wrapDitolak');
                wrapDitolak.classList.toggle('d-none', !data.alasan_penolakan);
                if (data.alasan_penolakan) {
                    wrapDitolak.innerHTML =
                        `<strong>Telah Ditolak</strong> oleh <strong>${data.ditolak_oleh}</strong> pada ${data.tanggal_ditolak}.<br><strong>Alasan:</strong> ${data.alasan_penolakan}`;
                }
            }

            function resetModalUI() {
                // ... (Fungsi ini tidak perlu diubah) ...
                const container = document.getElementById('hasil-kemiripan-container');
                container.style.display = 'none';
                container.innerHTML = '';

                const btnCek = document.getElementById('btn-cek-kemiripan');
                btnCek.style.display = 'block';
                btnCek.disabled = false;
                btnCek.innerHTML = '<i class="bi bi-search"></i> Lakukan Pengecekan';

                document.getElementById('modalNama').innerText = '';
                document.getElementById('modalNim').innerText = '';
                document.getElementById('modalProdi').innerText = '';
                document.getElementById('modalJudul').innerText = '';

                document.getElementById('wrapDiterima').classList.add('d-none');
                document.getElementById('wrapDitolak').classList.add('d-none');
            }
        });
    </script>
@endpush
