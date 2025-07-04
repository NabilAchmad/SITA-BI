@php
    // Cek apakah tugas akhir ini sudah memiliki pembimbing 1 dari alur tawaran topik
    $pembimbing1 = $tugasAkhir->peranDosenTA->where('peran', 'pembimbing1')->first();
@endphp

<div class="modal fade" id="modalTetapkanPembimbing-{{ $tugasAkhir->id }}" tabindex="-1"
    aria-labelledby="modalLabel-{{ $tugasAkhir->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0"> {{-- Added shadow and border-0 for modern look --}}
            <div class="modal-header bg-primary text-white border-bottom-0 rounded-top"> {{-- Rounded top corners --}}
                <h5 class="modal-title" id="modalLabel-{{ $tugasAkhir->id }}">
                    <i class="bi bi-person-lines-fill me-2"></i> Tetapkan Pembimbing Tugas Akhir
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('penugasan-bimbingan.store', $tugasAkhir->id) }}" method="POST"
                class="form-assign-pembimbing">
                @csrf
                @method('PUT')
                <div class="modal-body p-4"> {{-- Increased padding --}}
                    <div class="card mb-4 border-info bg-light"> {{-- Card for student and title info --}}
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
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-check-fill me-2"></i> Pembimbing 1
                            </h6>
                            @if ($pembimbing1)
                                <div class="card bg-light-subtle border-success shadow-sm"> {{-- Subtle success card for fixed P1 --}}
                                    <div class="card-body py-3 px-4 d-flex align-items-center">
                                        <i class="bi bi-lock-fill text-success me-3 fs-5"></i>
                                        <div>
                                            <p class="mb-0 fw-bold">{{ $pembimbing1->dosen->user->name }}</p>
                                            <small class="text-muted">Otomatis dari alur Tawaran Topik.</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="pembimbing1" value="{{ $pembimbing1->dosen->id }}">
                            @else
                                <div class="mb-2">
                                    <input type="text" class="form-control live-search-dosen-input"
                                        data-target-container="dosen-list-pembimbing1-{{ $tugasAkhir->id }}"
                                        placeholder="Cari nama dosen Pembimbing 1...">
                                </div>
                                <div id="dosen-list-pembimbing1-{{ $tugasAkhir->id }}"
                                    class="dosen-selection-container" data-pembimbing-role="pembimbing1">
                                    {{-- Dosen cards will be populated here by JS --}}
                                    @foreach ($dosenList as $dosen)
                                        <div class="card dosen-card mb-2" data-dosen-id="{{ $dosen->id }}">
                                            <div class="card-body p-3">
                                                <i class="bi bi-person-circle me-2 text-muted"></i>
                                                {{ $dosen->user->name }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="pembimbing1"
                                    id="selected-pembimbing1-{{ $tugasAkhir->id }}" required>
                                <div class="invalid-feedback">Silakan pilih Pembimbing 1.</div>
                            @endif
                        </div>

                        {{-- Kolom Pembimbing 2 --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-check me-2"></i> Pembimbing 2
                            </h6>
                            <div class="mb-2">
                                <input type="text" class="form-control live-search-dosen-input"
                                    data-target-container="dosen-list-pembimbing2-{{ $tugasAkhir->id }}"
                                    placeholder="Cari nama dosen Pembimbing 2...">
                            </div>
                            <div id="dosen-list-pembimbing2-{{ $tugasAkhir->id }}" class="dosen-selection-container"
                                data-pembimbing-role="pembimbing2">
                                {{-- Dosen cards will be populated here by JS --}}
                                @foreach ($dosenList as $dosen)
                                    <div class="card dosen-card mb-2" data-dosen-id="{{ $dosen->id }}">
                                        <div class="card-body p-3">
                                            <i class="bi bi-person-circle me-2 text-muted"></i>
                                            {{ $dosen->user->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="pembimbing2" id="selected-pembimbing2-{{ $tugasAkhir->id }}"
                                required>
                            <div class="invalid-feedback">Silakan pilih Pembimbing 2.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end border-top-0 pt-3"> {{-- Aligned buttons to end, no top border --}}
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal[id^="modalTetapkanPembimbing-"]');

            modals.forEach(modal => {
                const p1SearchInput = modal.querySelector('input[data-target-container*="pembimbing1"]');
                const p2SearchInput = modal.querySelector('input[data-target-container*="pembimbing2"]');
                const p1Container = modal.querySelector('#dosen-list-pembimbing1-{{ $tugasAkhir->id }}');
                const p2Container = modal.querySelector('#dosen-list-pembimbing2-{{ $tugasAkhir->id }}');
                const hiddenInputP1 = modal.querySelector('input[name="pembimbing1"]');
                const hiddenInputP2 = modal.querySelector('input[name="pembimbing2"]');

                // Ambil daftar lengkap dosen dari elemen yang sudah ada saat halaman dimuat
                const allDosenList = @json($dosenList->map(fn($d) => ['id' => (string) $d->id, 'name' => $d->user->name]));

                const updateUI = () => {
                    const selectedP1Id = hiddenInputP1.value;
                    const selectedP2Id = hiddenInputP2.value;

                    // --- Render list untuk Pembimbing 1 (jika ada) ---
                    if (p1Container) {
                        const searchTerm = p1SearchInput.value.toLowerCase();
                        const filteredDosen = allDosenList.filter(d =>
                            d.name.toLowerCase().includes(searchTerm) && d.id !== selectedP2Id
                        );
                        renderDosenList(p1Container, filteredDosen, selectedP1Id, 'pembimbing1');
                    }

                    // --- Render list untuk Pembimbing 2 ---
                    if (p2Container) {
                        const searchTerm = p2SearchInput.value.toLowerCase();
                        const filteredDosen = allDosenList.filter(d =>
                            d.name.toLowerCase().includes(searchTerm) && d.id !== selectedP1Id
                        );
                        renderDosenList(p2Container, filteredDosen, selectedP2Id, 'pembimbing2');
                    }
                };

                const renderDosenList = (container, dosenArray, selectedId, role) => {
                    container.innerHTML = ''; // Kosongkan daftar saat ini

                    dosenArray.forEach(dosen => {
                        const card = document.createElement('div');
                        card.className = 'card dosen-card mb-2';
                        card.dataset.dosenId = dosen.id;
                        card.innerHTML =
                            `<div class="card-body p-3"><i class="bi bi-person-circle me-2 text-muted"></i> ${dosen.name}</div>`;

                        if (dosen.id === selectedId) {
                            card.classList.add('active');
                        }

                        card.addEventListener('click', () => {
                            const clickedId = card.dataset.dosenId;
                            if (role === 'pembimbing1') {
                                hiddenInputP1.value = clickedId;
                            } else {
                                hiddenInputP2.value = clickedId;
                            }
                            // Setelah memilih, panggil updateUI untuk me-render ulang kedua list
                            updateUI();
                        });

                        container.appendChild(card);
                    });
                };

                // --- Tambahkan Event Listeners untuk Pencarian ---
                if (p1SearchInput) {
                    p1SearchInput.addEventListener('keyup', updateUI);
                }
                if (p2SearchInput) {
                    p2SearchInput.addEventListener('keyup', updateUI);
                }

                // --- Inisialisasi saat Modal dibuka ---
                modal.addEventListener('show.bs.modal', () => {
                    // Reset input pencarian
                    if (p1SearchInput) p1SearchInput.value = '';
                    if (p2SearchInput) p2SearchInput.value = '';
                    // Render UI sesuai state awal
                    updateUI();
                });
            });
        });
    </script>
@endpush

<style>
    /* General Modal Enhancements */
    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .modal-header {
        border-bottom: none;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    /* Supervisor Selection Containers */
    .dosen-selection-container {
        max-height: 250px;
        /* Adjust height as needed */
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        /* Lighter border */
        border-radius: 0.5rem;
        /* Smoother corners */
        padding: 10px;
        background-color: #fcfcfc;
        /* Slightly off-white background */
    }

    /* Individual Dosen Cards */
    .dosen-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: 1px solid #e9ecef;
        /* Light gray border */
        border-radius: 0.4rem;
        /* Match container border-radius */
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.03);
        /* Subtle shadow */
    }

    .dosen-card:hover {
        background-color: #e2f0ff;
        /* Light blue on hover */
        border-color: #007bff;
        /* Primary color border on hover */
        transform: translateY(-2px);
        /* Slight lift effect */
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
        /* More prominent shadow */
    }

    .dosen-card.active {
        background-color: #007bff;
        /* Primary color when active */
        color: white;
        border-color: #007bff;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 123, 255, 0.25);
        /* Stronger shadow for active */
    }

    .dosen-card.active .bi-person-circle,
    .dosen-card.active .text-muted {
        color: white !important;
        /* Ensure icons and muted text are white when active */
    }

    /* Fixed Pembimbing 1 Card Styling */
    .card.bg-light-subtle.border-success {
        background-color: #eaf7ed !important;
        /* A light green tint */
        border-color: #28a745 !important;
        /* Green border */
    }
</style>
