@php
    // Mendapatkan data pembimbing yang sudah ada untuk pre-selection
    $pembimbing1 = $tugasAkhir->pembimbingSatu; // Menggunakan accessor
    $pembimbing2 = $tugasAkhir->pembimbingDua; // Menggunakan accessor

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

            <form action="{{ route('jurusan.penugasan-pembimbing.update', $tugasAkhir->id) }}" method="POST"
                class="form-edit-pembimbing">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    {{-- Bagian informasi mahasiswa dan judul --}}
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
                        {{-- Kolom Pembimbing 1 --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-check-fill me-2"></i>
                                Pembimbing 1</h6>
                            @if ($isFromTawaranTopik && $pembimbing1)
                                <div class="card bg-light-subtle border-success shadow-sm">
                                    <div class="card-body py-3 px-4 d-flex align-items-center">
                                        <i class="bi bi-lock-fill text-success me-3 fs-5"></i>
                                        <div>
                                            <p class="mb-0 fw-bold">{{ $pembimbing1->dosen->user->name ?? 'N/A' }}</p>
                                            <small class="text-muted">Otomatis dari alur Tawaran Topik.</small>
                                        </div>
                                    </div>
                                </div>
                                {{-- ✅ PERBAIKAN: Nama input disesuaikan --}}
                                <input type="hidden" name="pembimbing_1_id" value="{{ $pembimbing1->dosen_id ?? '' }}">
                            @else
                                <div class="mb-2">
                                    <input type="text" class="form-control live-search-dosen-input"
                                        data-pembimbing-role="pembimbing1"
                                        placeholder="Cari nama dosen Pembimbing 1...">
                                </div>
                                <div class="dosen-selection-container" data-pembimbing-role="pembimbing1"></div>
                                {{-- ✅ PERBAIKAN: Nama input disesuaikan --}}
                                <input type="hidden" name="pembimbing_1_id" class="selected-pembimbing-input"
                                    value="{{ $pembimbing1->dosen_id ?? '' }}" required>
                                <div class="invalid-feedback">Silakan pilih Pembimbing 1.</div>
                            @endif
                        </div>

                        {{-- Kolom Pembimbing 2 --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-check me-2"></i> Pembimbing 2
                            </h6>
                            <div class="mb-2">
                                <input type="text" class="form-control live-search-dosen-input"
                                    data-pembimbing-role="pembimbing2" placeholder="Cari nama dosen Pembimbing 2...">
                            </div>
                            <div class="dosen-selection-container" data-pembimbing-role="pembimbing2"></div>
                            {{-- ✅ PERBAIKAN: Nama input disesuaikan dan tidak required --}}
                            <input type="hidden" name="pembimbing_2_id" class="selected-pembimbing-input"
                                value="{{ $pembimbing2->dosen_id ?? '' }}">
                            <div class="invalid-feedback">Silakan pilih Pembimbing 2.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end border-top-0 pt-3">
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    {{-- Menampilkan tombol hanya jika pengguna adalah Kajur atau Kaprodi --}}
                    @if (auth()->user()->hasAnyRole(['kajur', 'kaprodi-d3', 'kaprodi-d4']))
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil daftar lengkap dosen SEKALI saja saat halaman dimuat
            const allDosenList = @json($dosenList->map(fn($d) => ['id' => (string) $d->id, 'name' => $d->user->name]));

            // Inisialisasi setiap modal edit
            document.querySelectorAll('.modal[id^="modalEditPembimbing-"]').forEach(modal => {
                const searchInputs = modal.querySelectorAll('.live-search-dosen-input');
                const containers = {
                    pembimbing1: modal.querySelector(
                        '.dosen-selection-container[data-pembimbing-role="pembimbing1"]'),
                    pembimbing2: modal.querySelector(
                        '.dosen-selection-container[data-pembimbing-role="pembimbing2"]')
                };
                const hiddenInputs = {
                    pembimbing1: modal.querySelector('input[name="pembimbing_1_id"]'),
                    pembimbing2: modal.querySelector('input[name="pembimbing_2_id"]')
                };

                const updateUI = () => {
                    const selectedP1Id = hiddenInputs.pembimbing1.value;
                    const selectedP2Id = hiddenInputs.pembimbing2.value;

                    // Render list untuk Pembimbing 1 (jika ada)
                    if (containers.pembimbing1) {
                        const searchInput = modal.querySelector(
                            '.live-search-dosen-input[data-pembimbing-role="pembimbing1"]');
                        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                        const filteredDosen = allDosenList.filter(d =>
                            d.name.toLowerCase().includes(searchTerm) && d.id !== selectedP2Id
                        );
                        renderDosenList(containers.pembimbing1, filteredDosen, selectedP1Id,
                            'pembimbing1');
                    }

                    // Render list untuk Pembimbing 2
                    if (containers.pembimbing2) {
                        const searchInput = modal.querySelector(
                            '.live-search-dosen-input[data-pembimbing-role="pembimbing2"]');
                        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                        const filteredDosen = allDosenList.filter(d =>
                            d.name.toLowerCase().includes(searchTerm) && d.id !== selectedP1Id
                        );
                        renderDosenList(containers.pembimbing2, filteredDosen, selectedP2Id,
                            'pembimbing2');
                    }
                };

                const renderDosenList = (container, dosenArray, selectedId, role) => {
                    if (!container) return;
                    container.innerHTML = '';
                    dosenArray.forEach(dosen => {
                        const card = document.createElement('div');
                        card.className =
                            `card dosen-card mb-2 ${dosen.id === selectedId ? 'active' : ''}`;
                        card.dataset.dosenId = dosen.id;
                        card.innerHTML =
                            `<div class="card-body p-3"><i class="bi bi-person-circle me-2 text-muted"></i> ${dosen.name}</div>`;

                        card.addEventListener('click', () => {
                            // Toggle selection
                            const currentVal = hiddenInputs[role].value;
                            hiddenInputs[role].value = (currentVal === card.dataset
                                .dosenId) ? '' : card.dataset.dosenId;
                            updateUI();
                        });
                        container.appendChild(card);
                    });
                };

                searchInputs.forEach(input => input.addEventListener('keyup', updateUI));

                modal.addEventListener('show.bs.modal', () => {
                    searchInputs.forEach(input => input.value = '');
                    updateUI(); // Render UI awal saat modal dibuka
                });
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
