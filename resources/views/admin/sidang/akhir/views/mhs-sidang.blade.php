@extends('layouts.template.main')

@section('title', 'Kelola Jadwal Sidang Akhir')

@section('content')
    <div class="container-fluid">
        {{-- Header dan Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Kelola Jadwal Sidang Akhir</h1>
                <p class="text-muted mb-0">Manajemen jadwal, penguji, dan status kelulusan untuk sidang akhir.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('jurusan.penjadwalan-sidang.index') }}">Dashboard Sidang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal Akhir</li>
                </ol>
            </nav>
        </div>

        {{-- Filter Prodi dan Pencarian --}}
        @include('admin.sidang.akhir.partials.filters')

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                {{-- Navigasi Tabs --}}
                <ul class="nav nav-tabs mb-3" id="jadwalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab"
                            data-bs-target="#menunggu-pane" type="button" role="tab" aria-controls="menunggu-pane"
                            aria-selected="true">Menunggu Jadwal <span
                                class="badge bg-secondary rounded-pill">{{ $mahasiswaMenunggu?->total() ?? 0 }}</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dijadwalkan-tab" data-bs-toggle="tab"
                            data-bs-target="#dijadwalkan-pane" type="button" role="tab"
                            aria-controls="dijadwalkan-pane" aria-selected="false">Dijadwalkan <span
                                class="badge bg-primary rounded-pill">{{ $jadwalMahasiswa?->total() ?? 0 }}</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tidak-lulus-tab" data-bs-toggle="tab"
                            data-bs-target="#tidak-lulus-pane" type="button" role="tab"
                            aria-controls="tidak-lulus-pane" aria-selected="false">Mengulang <span
                                class="badge bg-danger rounded-pill">{{ $mahasiswaTidakLulus?->total() ?? 0 }}</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lulus-tab" data-bs-toggle="tab" data-bs-target="#lulus-pane"
                            type="button" role="tab" aria-controls="lulus-pane" aria-selected="false">Lulus <span
                                class="badge bg-success rounded-pill">{{ $mahasiswaLulus?->total() ?? 0 }}</span></button>
                    </li>
                </ul>

                @php
                    // Definisikan header untuk setiap tabel agar mudah dikelola
                    $headersMahasiswa = ['No', 'Nama', 'NIM', 'Prodi', 'Judul TA', 'Status', 'Aksi'];
                    $headersJadwal = ['No', 'Nama', 'Judul TA', 'Tanggal', 'Waktu', 'Ruangan', 'Aksi'];
                @endphp

                {{-- Konten Tabs --}}
                <div class="tab-content" id="jadwalTabContent">
                    <x-admin.sidang.tab-pane id="menunggu-pane" labelledby="menunggu-tab" :isActive="true"
                        :columns="$headersMahasiswa" :tableData="$mahasiswaMenunggu" partial="admin.sidang.akhir.partials._table-rows" :type="'menunggu'" />
                    <x-admin.sidang.tab-pane id="dijadwalkan-pane" labelledby="dijadwalkan-tab" :columns="$headersJadwal"
                        :tableData="$jadwalMahasiswa" partial="admin.sidang.akhir.partials._table-rows" :type="'dijadwalkan'" />
                    <x-admin.sidang.tab-pane id="tidak-lulus-pane" labelledby="tidak-lulus-tab" :columns="$headersMahasiswa"
                        :tableData="$mahasiswaTidakLulus" partial="admin.sidang.akhir.partials._table-rows" :type="'tidak-lulus'" />
                    <x-admin.sidang.tab-pane id="lulus-pane" labelledby="lulus-tab" :columns="$headersMahasiswa" :tableData="$mahasiswaLulus"
                        partial="admin.sidang.akhir.partials._table-rows" :type="'lulus-sempro'" />
                </div>
            </div>
        </div>
    </div>

    <!-- Wadah untuk modal yang akan dibuat oleh JavaScript -->
    <div id="modalContainer"></div>

    {{-- Template untuk modal, disembunyikan dan akan di-clone oleh JavaScript --}}
    @include('admin.sidang.akhir.modal.penguji')
    @include('admin.sidang.akhir.modal.jadwal-sidang')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalContainer = document.getElementById('modalContainer');

            // --- Event Delegation untuk semua pemicu modal ---
            document.body.addEventListener('click', function(e) {
                const jadwalBtn = e.target.closest('.btn-jadwalkan');
                if (jadwalBtn) {
                    const data = {
                        sidangId: jadwalBtn.dataset.tugasAkhirId,
                        nama: jadwalBtn.dataset.nama,
                        nim: jadwalBtn.dataset.nim,
                        judul: jadwalBtn.dataset.judul,
                        urlPenguji: jadwalBtn.dataset.urlPenguji
                    };
                    openPengujiModal(data);
                }
            });

            // --- Modal 1: Pilih Penguji ---
            async function openPengujiModal(data) {
                const template = document.getElementById('template-modal-penguji');
                if (!template) return;

                const clone = template.content.cloneNode(true);
                const form = clone.querySelector('#form-penguji');
                form.action = data.urlPenguji;

                modalContainer.innerHTML = '';
                modalContainer.appendChild(clone);

                const modalEl = document.getElementById('modalPenguji');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();

                handlePengujiFormSubmit(form, bsModal, data);
                limitCheckboxSelection(modalEl);
                setupSearchFilter(modalEl);
            }

            async function handlePengujiFormSubmit(form, bsModal, originalData) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            let errorMsg = result.message || 'Terjadi kesalahan server.';
                            if (response.status === 422 && result.errors) {
                                errorMsg = Object.values(result.errors).flat().join('\n');
                            }
                            swal("Gagal", errorMsg, "warning");
                            return;
                        }

                        if (result.success) {
                            bsModal.hide();
                            openJadwalModal(originalData);
                        } else {
                            swal("Gagal", result.message || 'Gagal menyimpan penguji.', "error");
                        }
                    } catch (error) {
                        swal("Error", "Terjadi kesalahan koneksi.", "error");
                    }
                });
            }

            // --- Modal 2: Isi Jadwal Sidang ---
            function openJadwalModal(data) {
                const template = document.getElementById('template-modal-jadwal-sidang');
                if (!template) return;

                const clone = template.content.cloneNode(true);
                clone.querySelector('#jadwal-sidang_id').value = data.sidangId;
                clone.querySelector('#jadwal-nama').value = data.nama;
                clone.querySelector('#jadwal-nim').value = data.nim;
                clone.querySelector('#jadwal-judul').value = data.judul;

                modalContainer.innerHTML = '';
                modalContainer.appendChild(clone);

                const modalEl = document.getElementById('modalJadwalSidang');
                const form = modalEl.querySelector('#form-jadwal-sidang');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();

                handleJadwalFormSubmit(form, bsModal);
            }

            async function handleJadwalFormSubmit(form, bsModal) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            let errorMsg = result.message || 'Terjadi kesalahan server.';
                            if (response.status === 422 && result.errors) {
                                errorMsg = Object.values(result.errors).flat().join('\n');
                            }
                            swal("Gagal", errorMsg, "warning");
                            return;
                        }

                        if (result.success) {
                            bsModal.hide();
                            swal({
                                title: "Berhasil!",
                                text: result.message || "Jadwal sidang berhasil dibuat.",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        text: "OK",
                                        className: "btn btn-primary"
                                    }
                                }
                            }).then(() => {
                                window.location.href =
                                    "{{ route('jurusan.penjadwalan-sidang.detail') }}";
                            });
                        } else {
                            swal("Gagal", result.message || 'Gagal menyimpan jadwal.', "error");
                        }
                    } catch (error) {
                        swal("Error", "Terjadi kesalahan koneksi.", "error");
                    }
                });
            }

            // --- Fungsi Helper ---
            function limitCheckboxSelection(modal) {
                const checkboxes = modal.querySelectorAll('input[type=checkbox][name="penguji[]"]');
                checkboxes.forEach(chk => {
                    chk.addEventListener('change', () => {
                        const checkedCount = [...checkboxes].filter(c => c.checked).length;
                        if (checkedCount > 4) {
                            chk.checked = false;
                            swal("Peringatan!", "Anda hanya dapat memilih maksimal 4 penguji.",
                                "warning");
                        }
                    });
                });
            }

            function setupSearchFilter(modal) {
                const searchInput = modal.querySelector('#search-dosen');
                const tbody = modal.querySelector('#tbody-dosen');
                if (!searchInput || !tbody) return;
                searchInput.addEventListener('input', () => {
                    const val = searchInput.value.toLowerCase();
                    tbody.querySelectorAll('tr.dosen-item').forEach(row => {
                        const name = row.querySelector('.nama-dosen').textContent.toLowerCase();
                        row.style.display = name.includes(val) ? '' : 'none';
                    });
                });
            }

            // Menampilkan notifikasi session jika ada
            @if (session('success'))
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            className: "btn btn-primary"
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
