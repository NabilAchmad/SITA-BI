@extends('layouts.template.kajur')

@section('title', 'Kelola Jadwal Sidang Sempro')

@section('content')
<div class="container-fluid">
    <!-- Header Section with Animation -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h1 class="fw-bold text-primary display-6">
                <i class="bi bi-calendar-x me-2 animate__animated animate__bounce"></i>Kelola Jadwal Sidang Sempro
            </h1>
            <p class="text-muted lead mb-0">Manajemen jadwal sidang proposal mahasiswa</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('sidangDashboard.kajur') }}" class="text-decoration-none hover-effect">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal</li>
            </ol>
        </nav>
    </div>

    <!-- Enhanced Program Study Tabs -->
    <ul class="nav nav-pills mb-4 custom-nav-pills">
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') == null ? 'active pulse' : '' }}"
                href="{{ route('kajur.sidang.menunggu.sempro') }}">
                <i class="bi bi-grid-3x3-gap me-1"></i> Semua
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') === 'D4' ? 'active pulse' : '' }}"
                href="{{ route('kajur.sidang.menunggu.sempro', ['prodi' => 'D4']) }}">
                <i class="bi bi-mortarboard me-1"></i> D4
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('prodi') === 'D3' ? 'active pulse' : '' }}"
                href="{{ route('kajur.sidang.menunggu.sempro', ['prodi' => 'D3']) }}">
                <i class="bi bi-mortarboard me-1"></i> D3
            </a>
        </li>
    </ul>

    <!-- Modern Search Form -->
    <form method="GET" action="{{ route('kajur.sidang.menunggu.sempro') }}" class="mb-4">
        <div class="input-group input-group-lg shadow-sm hover-effect">
            <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" id="searchInput" class="form-control form-control-lg"
                placeholder="Cari berdasarkan nama atau NIM..." value="{{ request('search') }}" autocomplete="off">
            <button class="btn btn-primary px-4 hover-effect" type="submit">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>
    </form>

    <!-- Main Content Card -->
    <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <!-- Enhanced Schedule Tabs -->
            <ul class="nav nav-pills nav-fill mb-4 custom-tabs" id="jadwalTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 hover-effect" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                        type="button" role="tab">
                        <i class="bi bi-hourglass me-2"></i>Menunggu Jadwal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 hover-effect" id="tidak-lulus-tab" data-bs-toggle="tab" data-bs-target="#tidak-lulus"
                        type="button" role="tab">
                        <i class="bi bi-arrow-repeat me-2"></i>Mengulang Sidang
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="jadwalTabContent">
                <!-- Waiting Schedule Tab -->
                <div class="tab-pane fade show active" id="menunggu" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle custom-table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th>Judul Tugas Akhir</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="menunggu">
                                @foreach($mahasiswaMenunggu as $index => $mhs)
                                <tr class="hover-row">
                                    <td class="text-center">{{ $index + $mahasiswaMenunggu->firstItem() }}</td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $mhs->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td><span class="badge bg-info pulse">{{ $mhs->prodi }}</span></td>
                                    <td>{{ $mhs->tugasAkhir->judul ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning pulse">Menunggu Penjadwalan</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm btn-jadwalkan hover-effect"
                                            data-sidang-id="{{ $mhs->tugasAkhir->sidangTerakhir->id ?? '' }}"
                                            data-url="{{ route('kajur.sidang.jadwal.sempro') }}"
                                            data-nama="{{ $mhs->user->name ?? '' }}"
                                            data-nim="{{ $mhs->nim }}"
                                            data-judul="{{ $mhs->tugasAkhir->judul ?? '' }}">
                                            <i class="bi bi-calendar-plus me-1"></i> Jadwalkan
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Failed Students Tab -->
                <div class="tab-pane fade" id="tidak-lulus" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle custom-table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul Tugas Akhir</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tidak-lulus">
                                @foreach($mahasiswaTidakLulus as $index => $mhs)
                                <tr class="hover-row">
                                    <td class="text-center">{{ $index + $mahasiswaTidakLulus->firstItem() }}</td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $mhs->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->tugasAkhir->judul ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger pulse">Tidak Lulus</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm btn-jadwalkan hover-effect"
                                            data-sidang-id="{{ $mhs->tugasAkhir->sidangTerakhir->id ?? '' }}"
                                            data-url="{{ route('kajur.sidang.jadwal.sempro') }}"
                                            data-nama="{{ $mhs->user->name ?? '' }}"
                                            data-nim="{{ $mhs->nim }}"
                                            data-judul="{{ $mhs->tugasAkhir->judul ?? '' }}">
                                            <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Ulang
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $mahasiswaMenunggu->links() }}
        {{ $mahasiswaTidakLulus->links() }}
    </div>
</div>

<!-- Modal container -->
<div id="modalContainer"></div>

{{-- @include('kajur.sidang.sempro.modal.penguji') --}}
{{-- @include('kajur.sidang.sempro.modal.jadwal-sidang') --}}

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let modalContainer = document.getElementById('modalContainer');
        let currentPengujiModal = null;
        let currentJadwalModal = null;

        function limitCheckboxSelection() {
            const checkboxes = currentPengujiModal.querySelectorAll('input[type=checkbox][name="penguji[]"]');
            checkboxes.forEach(chk => {
                chk.addEventListener('change', () => {
                    const checkedCount = [...checkboxes].filter(c => c.checked).length;
                    if (checkedCount > 4) {
                        chk.checked = false;
                        swal({
                            title: "Peringatan!",
                            text: "Anda hanya dapat memilih maksimal 4 penguji.",
                            icon: "warning",
                            buttons: {
                                confirm: {
                                    text: "OK",
                                    className: "btn btn-primary"
                                }
                            }
                        });
                    }
                });
            });
        }

        function setupSearchFilter() {
            const searchInput = currentPengujiModal.querySelector('#search-dosen');
            const tbody = currentPengujiModal.querySelector('#tbody-dosen');
            searchInput.addEventListener('input', () => {
                const val = searchInput.value.toLowerCase();
                tbody.querySelectorAll('tr.dosen-item').forEach(row => {
                    const name = row.querySelector('.nama-dosen').textContent.toLowerCase();
                    row.style.display = name.includes(val) ? '' : 'none';
                });
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-jadwalkan')) {
                const btn = e.target.closest('.btn-jadwalkan');

                modalContainer.innerHTML = '';
                const templatePenguji = document.getElementById('template-modal-penguji');
                const clone = templatePenguji.content.cloneNode(true);
                modalContainer.appendChild(clone);

                currentPengujiModal = document.getElementById('modalPenguji');

                const formPenguji = document.getElementById('form-penguji');
                const sidangId = btn.dataset.sidangId;
                formPenguji.action = btn.dataset.url;

                const bsModalPenguji = new bootstrap.Modal(currentPengujiModal);
                bsModalPenguji.show();

                limitCheckboxSelection();
                setupSearchFilter();

                currentPengujiModal.querySelector('#batal-penguji').addEventListener('click', () => {
                    bsModalPenguji.hide();
                });

                formPenguji.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData(formPenguji);

                    fetch(formPenguji.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    const errorData = await response.json();
                                    const messages = Object.values(errorData.errors).flat().join('\n');
                                    swal({
                                        title: "Validasi Gagal!",
                                        text: messages,
                                        icon: "error",
                                        buttons: {
                                            confirm: {
                                                text: "OK",
                                                className: "btn btn-primary"
                                            }
                                        }
                                    });
                                } else {
                                    throw new Error('Terjadi kesalahan server.');
                                }
                                throw new Error('Fetch error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                bsModalPenguji.hide();
                                openModalJadwalSidang({
                                    sidang_id: sidangId,
                                    nama: btn.dataset.nama,
                                    nim: btn.dataset.nim,
                                    judul: btn.dataset.judul
                                });
                            } else {
                                swal({
                                    title: "Gagal!",
                                    text: data.message || 'Gagal menyimpan penguji.',
                                    icon: "error",
                                    buttons: {
                                        confirm: {
                                            text: "OK",
                                            className: "btn btn-primary"
                                        }
                                    }
                                });
                            }
                        })
                        .catch(err => {
                            if (err.message !== 'Fetch error') {
                                swal({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat menyimpan penguji.",
                                    icon: "error",
                                    buttons: {
                                        confirm: {
                                            text: "OK",
                                            className: "btn btn-primary"
                                        }
                                    }
                                });
                            }
                        });
                }, {
                    once: true
                });
            }
        });
