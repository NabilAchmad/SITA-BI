@extends('layouts.template.main')
@section('title', 'Validasi Judul Tugas Akhir')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-journal-text me-2"></i> Daftar Pengajuan Judul Tugas Akhir</h5>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link {{ request('prodi') == null ? 'active' : '' }}" href="?">All</a></li>
            <li class="nav-item"><a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}" href="?prodi=D4">D4</a>
            </li>
            <li class="nav-item"><a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}" href="?prodi=D3">D3</a>
            </li>
        </ul>

        {{-- Search --}}
        <form method="GET" class="mb-3">
            <input type="hidden" name="prodi" value="{{ request('prodi') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugasAkhir as $ta)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ta->mahasiswa->user->name }}</td>
                            <td>{{ $ta->mahasiswa->nim }}</td>
                            <td>
                                {{ $ta->mahasiswa->prodi === 'd3' ? 'D3 Bahasa Inggris' : ($ta->mahasiswa->prodi === 'd4' ? 'D4 Bahasa Inggris' : '-') }}
                            </td>
                            <td>{{ $ta->judul }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-id="{{ $ta->id }}"
                                    onclick="showDetail(this)">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetailTA" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Judul Tugas Akhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama:</strong> <span id="modalNama"></span></p>
                    <p><strong>NIM:</strong> <span id="modalNim"></span></p>
                    <p><strong>Program Studi:</strong> <span id="modalProdi"></span></p>
                    <p><strong>Judul:</strong> <span id="modalJudul"></span></p>

                    <hr>
                    <p><strong>Judul yang Mirip:</strong></p>
                    <ul id="modalSimilar" class="ms-3"></ul>

                    <form method="POST" id="formValidasi" action="">
                        @csrf
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2">Setujui</button>
                            <button type="button" class="btn btn-danger" id="btnTolak">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formTolak" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalTolakLabel">Tolak Judul Tugas Akhir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Berikan alasan penolakan:</p>
                        <textarea name="komentar" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let modalDetailTA = null;
        let modalTolakTA = null;

        document.addEventListener('DOMContentLoaded', function() {
            modalDetailTA = new bootstrap.Modal(document.getElementById('modalDetailTA'));
            modalTolakTA = new bootstrap.Modal(document.getElementById('modalTolak'));

            // Jika modal tolak ditutup, buka kembali modal detail
            document.getElementById('modalTolak').addEventListener('hidden.bs.modal', function() {
                modalDetailTA.show();
            });
        });

        function showDetail(button) {
            const id = button.getAttribute('data-id');

            fetch(`/dosen/validasi/detail/${id}`)
                .then(res => res.json())
                .then(data => {
                    // Isi data ke modal
                    document.getElementById('modalNama').innerText = data.nama;
                    document.getElementById('modalNim').innerText = data.nim;
                    document.getElementById('modalProdi').innerText = data.prodi;
                    document.getElementById('modalJudul').innerText = data.judul;

                    // Judul mirip
                    const list = document.getElementById('modalSimilar');
                    list.innerHTML = '';
                    if (data.similar.length > 0) {
                        data.similar.forEach(judul => {
                            const li = document.createElement('li');
                            li.textContent = judul;
                            list.appendChild(li);
                        });
                    } else {
                        list.innerHTML = '<li class="text-muted">Tidak ditemukan judul serupa.</li>';
                    }

                    // Set form actions
                    document.getElementById('formValidasi').action = `/dosen/validasi/terima/${id}`;
                    document.getElementById('formTolak').action = `/dosen/validasi/tolak/${id}`;

                    modalDetailTA.show();

                    // Ketika tombol "Tolak" diklik, tutup modal detail dan buka modal tolak
                    document.getElementById('btnTolak').onclick = () => {
                        modalDetailTA.hide();
                        setTimeout(() => modalTolakTA.show(), 300);
                    };
                })
                .catch(err => {
                    alert("Gagal memuat detail. Silakan coba lagi.");
                    console.error(err);
                });
        }

        @if (session('success'))
            swal("Berhasil!", "{{ session('success') }}", "success");
        @endif

        @if (session('error'))
            swal("Gagal!", "{{ session('error') }}", "error");
        @endif
    </script>
@endpush
