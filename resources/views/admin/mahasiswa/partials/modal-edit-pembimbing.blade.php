{{-- 
    =========================================================================
    PENTING: File ini di-include dari list-mhs.blade.php dan menerima
    variabel $tugasAkhir dan $dosenList.
    =========================================================================
--}}

@php
    // Mendapatkan data pembimbing yang sudah ada untuk pre-selection
    $pembimbing1 = $tugasAkhir->peranDosenTA->where('peran', 'pembimbing1')->first();
    $pembimbing2 = $tugasAkhir->peranDosenTA->where('peran', 'pembimbing2')->first();
    // Cek apakah TA berasal dari tawaran topik
    $isFromTawaranTopik = !is_null($tugasAkhir->tawaran_topik_id);
@endphp

<div class="modal fade" id="modalEditPembimbing-{{ $tugasAkhir->id }}" tabindex="-1"
    aria-labelledby="modalLabel-{{ $tugasAkhir->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white border-bottom-0 rounded-top">
                <h5 class="modal-title" id="modalLabel-{{ $tugasAkhir->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit Pembimbing
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- ========================================================================= --}}
            {{-- PERBAIKAN #1 (KRITIS): Menggunakan nama route yang benar 'pembimbing.update' --}}
            {{-- ========================================================================= --}}
            <form action="{{ route('update', $tugasAkhir->id) }}" method="POST" class="form-edit-pembimbing"
                data-is-from-tawaran="{{ $isFromTawaranTopik ? 'true' : 'false' }}">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    {{-- Bagian informasi mahasiswa dan judul (Sudah Benar) --}}
                    <div class="card mb-4 border-info bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Tugas
                                Akhir</h6>
                            <p class="mb-1"><strong>Mahasiswa:</strong> {{ $tugasAkhir->mahasiswa->user->name }}
                                ({{ $tugasAkhir->mahasiswa->nim }})</p>
                            <p class="mb-0"><strong>Judul:</strong> {{ $tugasAkhir->judul }}</p>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Kolom Pembimbing 1 (Logika Blade Sudah Benar) --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-check-fill me-2"></i>
                                Pembimbing 1</h6>
                            @if ($isFromTawaranTopik)
                                <div class="card bg-light-subtle border-success shadow-sm">
                                    <div class="card-body py-3 px-4 d-flex align-items-center">
                                        <i class="bi bi-lock-fill text-success me-3 fs-5"></i>
                                        <div>
                                            <p class="mb-0 fw-bold">{{ $pembimbing1->dosen->user->name ?? 'N/A' }}</p>
                                            <small class="text-muted">Otomatis dari alur Tawaran Topik.</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="pembimbing1" value="{{ $pembimbing1->dosen_id ?? '' }}">
                            @else
                                <div class="mb-2">
                                    <input type="text" class="form-control live-search-dosen-input"
                                        data-target-container="dosen-list-edit-p1-{{ $tugasAkhir->id }}"
                                        placeholder="Cari nama dosen Pembimbing 1...">
                                </div>
                                <div id="dosen-list-edit-p1-{{ $tugasAkhir->id }}" class="dosen-selection-container"
                                    data-pembimbing-role="pembimbing1"></div>
                                <input type="hidden" name="pembimbing1" id="selected-edit-p1-{{ $tugasAkhir->id }}"
                                    value="{{ $pembimbing1->dosen_id ?? '' }}" required>
                                <div class="invalid-feedback">Silakan pilih Pembimbing 1.</div>
                            @endif
                        </div>

                        {{-- Kolom Pembimbing 2 (Logika Blade Sudah Benar) --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-check me-2"></i> Pembimbing 2
                            </h6>
                            <div class="mb-2">
                                <input type="text" class="form-control live-search-dosen-input"
                                    data-target-container="dosen-list-edit-p2-{{ $tugasAkhir->id }}"
                                    placeholder="Cari nama dosen Pembimbing 2...">
                            </div>
                            <div id="dosen-list-edit-p2-{{ $tugasAkhir->id }}" class="dosen-selection-container"
                                data-pembimbing-role="pembimbing2"></div>
                            <input type="hidden" name="pembimbing2" id="selected-edit-p2-{{ $tugasAkhir->id }}"
                                value="{{ $pembimbing2->dosen_id ?? '' }}" required>
                            <div class="invalid-feedback">Silakan pilih Pembimbing 2.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end border-top-0 pt-3">
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- PERBAIKAN #2: Logika JavaScript yang disempurnakan dan mandiri --}}
{{-- ========================================================================= --}}
@push('scripts')
    <script>
        // Gunakan $(document).ready() untuk memastikan semua elemen HTML,
        // termasuk yang di-include, sudah siap saat skrip dijalankan.
        $(document).ready(function() {

            // Ambil daftar dosen sekali saja saat halaman dimuat.
            const allDosenList = @json($dosenList->map(fn($d) => ['id' => (string) $d->id, 'name' => $d->user->name]));

            // Gunakan event delegation dari elemen statis terluar yang membungkus daftar mahasiswa Anda.
            // Ganti '#list-mhs-container' jika Anda memiliki ID lain untuk kontainer utama.
            const mainContainer = $('body'); // Menggunakan body lebih aman jika tidak ada kontainer spesifik

            // Logika untuk interaksi di dalam modal (pencarian, pemilihan)
            mainContainer.on('shown.bs.modal', '.modal[id^="modalEditPembimbing-"]', function() {
                const modal = this; // 'this' adalah elemen modal yang sedang aktif

                // Ambil semua elemen penting
                const p1Container = modal.querySelector('[data-pembimbing-role="pembimbing1"]');
                const p2Container = modal.querySelector('[data-pembimbing-role="pembimbing2"]');
                const searchInputP1 = modal.querySelector('[data-target-container*="dosen-list-edit-p1"]');
                const searchInputP2 = modal.querySelector('[data-target-container*="dosen-list-edit-p2"]');
                const hiddenInputP1 = modal.querySelector('input[name="pembimbing1"]');
                const hiddenInputP2 = modal.querySelector('input[name="pembimbing2"]');

                // Fungsi untuk render kartu dosen
                const renderDosenList = (container, dosenArray, selectedId) => {
                    if (!container) return;
                    container.innerHTML = '';
                    dosenArray.forEach(dosen => {
                        const card = document.createElement('div');
                        card.className = 'card dosen-card mb-2';
                        card.dataset.dosenId = dosen.id;
                        card.innerHTML =
                            `<div class="card-body p-3"><i class="bi bi-person-circle me-2 text-muted"></i> ${dosen.name}</div>`;
                        if (dosen.id === selectedId) {
                            card.classList.add('active');
                        }
                        container.appendChild(card);
                    });
                };

                // Fungsi utama untuk update UI
                const updateUI = () => {
                    const selectedP1Id = hiddenInputP1.value;
                    const selectedP2Id = hiddenInputP2.value;

                    if (p1Container && searchInputP1) {
                        const searchVal = searchInputP1.value.toLowerCase();
                        const filtered = allDosenList.filter(d => d.name.toLowerCase().includes(
                            searchVal) && d.id !== selectedP2Id);
                        renderDosenList(p1Container, filtered, selectedP1Id);
                    }
                    if (p2Container && searchInputP2) {
                        const searchVal = searchInputP2.value.toLowerCase();
                        const filtered = allDosenList.filter(d => d.name.toLowerCase().includes(
                            searchVal) && d.id !== selectedP1Id);
                        renderDosenList(p2Container, filtered, selectedP2Id);
                    }
                };

                // Hapus event listener lama untuk mencegah duplikasi, lalu pasang yang baru
                $(searchInputP1).off('keyup').on('keyup', updateUI);
                $(searchInputP2).off('keyup').on('keyup', updateUI);

                $(p1Container).off('click').on('click', '.dosen-card', function() {
                    hiddenInputP1.value = this.dataset.dosenId;
                    updateUI();
                });

                $(p2Container).off('click').on('click', '.dosen-card', function() {
                    hiddenInputP2.value = this.dataset.dosenId;
                    updateUI();
                });

                updateUI(); // Render awal saat modal dibuka
            });

            // =========================================================================
            // SATU-SATUNYA SUBMIT HANDLER MENGGUNAKAN JQUERY UNTUK MENGHINDARI KONFLIK
            // =========================================================================
            mainContainer.on('submit', 'form.form-edit-pembimbing', function(e) {
                e.preventDefault(); // Langkah #1: Selalu hentikan submit asli

                const form = $(this);
                const isFromTawaran = form.data('is-from-tawaran') === true;

                // Ambil nilai dan bersihkan dari spasi
                const p1 = form.find('input[name="pembimbing1"]').val().trim();
                const p2 = form.find('input[name="pembimbing2"]').val().trim();

                // Validasi #1: Tidak boleh sama
                if (p1 && p2 && p1 === p2) {
                    swal("Peringatan!", "Pembimbing 1 dan Pembimbing 2 tidak boleh orang yang sama.",
                        "warning");
                    return; // Menghentikan fungsi, tidak akan submit
                }

                // Validasi #2: Logika Cerdas
                if (isFromTawaran) {
                    if (!p2) {
                        swal("Peringatan!", "Anda harus memilih Pembimbing 2.", "warning");
                        return; // Menghentikan fungsi, tidak akan submit
                    }
                } else {
                    if (!p1 || !p2) {
                        swal("Peringatan!", "Anda harus memilih Pembimbing 1 dan Pembimbing 2.", "warning");
                        return; // Menghentikan fungsi, tidak akan submit
                    }
                }

                // Langkah #2: HANYA JIKA SEMUA VALIDASI LOLOS, submit form secara manual.
                // Menggunakan .get(0) untuk mendapatkan elemen DOM asli dan memanggil submit()
                // untuk menghindari loop tak terbatas dari event handler jQuery.
                this.submit();
            });

        });
    </script>
@endpush

@push('styles')
    <style>
        .dosen-selection-container {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            padding: 10px;
            background-color: #fcfcfc;
        }

        .dosen-card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 1px solid #e9ecef;
        }

        .dosen-card:hover {
            background-color: #e2f0ff;
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .dosen-card.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .dosen-card.active .bi,
        .dosen-card.active .text-muted {
            color: white !important;
        }
    </style>
@endpush
