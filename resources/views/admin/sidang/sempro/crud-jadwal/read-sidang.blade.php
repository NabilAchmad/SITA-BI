@extends('layouts.template.main') {{-- Ganti dengan layout utama Anda --}}

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold text-primary"><i class="bi bi-calendar-x me-2"></i> Kelola Jadwal Sidang Sempro</h1>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah terdaftar sidang sempro, termasuk yang belum
                    dijadwalkan dan yang mengulang sidang.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard-sidang') }}">Dashboard</a></li>
                    {{-- Ganti dengan route Anda --}}
                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal</li>
                </ol>
            </nav>
        </div>

        {{-- Tabs Program Studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}"
                    href="{{ route('sidang.kelola.sempro') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}"
                    href="{{ route('sidang.kelola.sempro', ['prodi' => 'D4']) }}">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}"
                    href="{{ route('sidang.kelola.sempro', ['prodi' => 'D3']) }}">D3</a>
            </li>
        </ul>

        <form method="GET" action="{{ route('sidang.kelola.sempro') }}">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="hidden" name="prodi" value="{{ request('prodi') }}"> {{-- Agar filter prodi tidak hilang saat search --}}
                <input type="text" name="search" id="searchInput" class="form-control"
                    placeholder="Cari nama atau NIM mahasiswa..." value="{{ request('search') }}" autocomplete="off">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                {{-- Tabs Status Jadwal --}}
                <ul class="nav nav-tabs mb-3" id="jadwalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                            type="button" role="tab" aria-controls="menunggu" aria-selected="true">
                            Menunggu Jadwal <span
                                class="badge bg-secondary rounded-pill">{{ $mahasiswaMenunggu->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dijadwalkan-tab" data-bs-toggle="tab" data-bs-target="#dijadwalkan"
                            type="button" role="tab" aria-controls="dijadwalkan" aria-selected="false">
                            Dijadwalkan <span class="badge bg-primary rounded-pill">{{ $jadwalMahasiswa->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tidak-lulus-tab" data-bs-toggle="tab" data-bs-target="#tidak-lulus"
                            type="button" role="tab" aria-controls="tidak-lulus" aria-selected="false">
                            Mengulang Sidang <span
                                class="badge bg-danger rounded-pill">{{ $mahasiswaTidakLulus->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lulus-sempro-tab" data-bs-toggle="tab" data-bs-target="#lulus-sempro"
                            type="button" role="tab" aria-controls="lulus-sempro" aria-selected="false">
                            Lulus Sempro <span
                                class="badge bg-success rounded-pill">{{ $mahasiswaLulusSempro->total() }}</span>
                        </button>
                    </li>
                </ul>

                @php
                    // Definisikan header untuk setiap tabel agar mudah dikelola
                    $headersMenunggu = ['No', 'Nama', 'NIM', 'Prodi', 'Judul TA', 'Status', 'Aksi'];
                    $headersDijadwalkan = [
                        'No',
                        'Nama',
                        'NIM',
                        'Prodi',
                        'Judul TA',
                        'Tanggal',
                        'Waktu',
                        'Ruangan',
                        'Aksi',
                    ];
                    $headersTidakLulus = ['No', 'Nama', 'NIM', 'Judul TA', 'Status', 'Aksi'];
                    $headersLulus = ['No', 'Nama', 'NIM', 'PRODI', 'Judul TA', 'Tanggal Lulus', 'Status'];
                @endphp

                <div class="tab-content" id="jadwalTabContent">
                    {{-- Memanggil komponen untuk setiap tab dengan props yang sesuai --}}
                    <x-admin.tab-pane id="menunggu" :collection="$mahasiswaMenunggu" :headers="$headersMenunggu"
                        partial="admin.sidang.sempro.partials._table-rows" :isActive="true" />
                    <x-admin.tab-pane id="dijadwalkan" :collection="$jadwalMahasiswa" :headers="$headersDijadwalkan"
                        partial="admin.sidang.sempro.partials._table-rows" />
                    <x-admin.tab-pane id="tidak-lulus" :collection="$mahasiswaTidakLulus" :headers="$headersTidakLulus"
                        partial="admin.sidang.sempro.partials._table-rows" />
                    <x-admin.tab-pane id="lulus-sempro" :collection="$mahasiswaLulusSempro" :headers="$headersLulus"
                        partial="admin.sidang.sempro.partials._table-rows" />
                </div>
            </div>
        </div>
    </div>

    <div id="modalContainer"></div>

    {{-- Pastikan modal ini dipanggil di luar perulangan, cukup sekali saja --}}
    @include('admin.sidang.akhir.modal.penguji')
    @include('admin.sidang.akhir.modal.jadwal-sidang')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk membuka Modal 1: Pilih Penguji
            function openPengujiModal(data) {
                const template = document.getElementById('template-modal-penguji');
                const clone = template.content.cloneNode(true);
                const modalContainer = document.getElementById('modalContainer');

                // Set action form dengan URL yang benar
                const form = clone.querySelector('#form-penguji');
                form.action = data.urlPenguji;

                modalContainer.innerHTML = '';
                modalContainer.appendChild(clone);
                const modalElement = modalContainer.querySelector('.modal');
                const bsModal = new bootstrap.Modal(modalElement);
                bsModal.show();

                // Tambahkan event listener untuk form penguji
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (!response.ok || !result.success) {
                            throw new Error(result.message || 'Gagal menyimpan penguji.');
                        }

                        // Jika sukses, tutup modal penguji dan buka modal jadwal
                        bsModal.hide();
                        // Kirimkan juga sidang_id yang mungkin didapat dari response
                        data.sidangId = result.sidang_id;
                        openJadwalModal(data);

                    } catch (error) {
                        // Tampilkan error (bisa menggunakan SweetAlert atau lainnya)
                        alert('Error: ' + error.message);
                    }
                });
            }

            // Fungsi untuk membuka Modal 2: Isi Jadwal Sidang
            function openJadwalModal(data) {
                const template = document.getElementById('template-modal-jadwal-sidang');
                const clone = template.content.cloneNode(true);
                const modalContainer = document.getElementById('modalContainer');

                // Isi data ke dalam elemen-elemen modal jadwal
                clone.querySelector('#jadwal-sidang_id').value = data.sidangId;
                clone.querySelector('#jadwal-nama').value = data.nama;
                clone.querySelector('#jadwal-nim').value = data.nim;
                clone.querySelector('#jadwal-judul').value = data.judul;

                modalContainer.innerHTML = '';
                modalContainer.appendChild(clone);
                const modalElement = modalContainer.querySelector('.modal');
                const bsModal = new bootstrap.Modal(modalElement);
                bsModal.show();

                // Event listener untuk form jadwal bisa ditambahkan di sini
            }

            // Event listener utama untuk memulai alur kerja
            document.body.addEventListener('click', function(event) {
                if (event.target.matches('.btn-workflow-jadwal')) {
                    const button = event.target;
                    const data = {
                        tugasAkhirId: button.dataset.tugasAkhirId,
                        sidangId: button.dataset.sidangId,
                        nama: button.dataset.nama,
                        nim: button.dataset.nim,
                        judul: button.dataset.judul,
                        urlPenguji: button.dataset.urlPenguji
                    };
                    openPengujiModal(data);
                }
            });
        });
    </script>
@endpush
