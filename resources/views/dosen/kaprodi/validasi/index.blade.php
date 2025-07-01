@extends('layouts.template.main')
@section('title', 'Validasi Judul Tugas Akhir')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold text-primary mb-3">
            <i class="bi bi-journal-text me-2"></i> Daftar Pengajuan Judul Tugas Akhir
        </h5>

        {{-- Filter Prodi --}}
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

        {{-- Tab Status --}}
        <ul class="nav nav-tabs mb-3" id="statusTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                    type="button" role="tab" aria-controls="menunggu" aria-selected="true">Menunggu Validasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="diterima-tab" data-bs-toggle="tab" data-bs-target="#diterima" type="button"
                    role="tab" aria-controls="diterima" aria-selected="false">Diterima</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak" type="button"
                    role="tab" aria-controls="ditolak" aria-selected="false">Ditolak</button>
            </li>
        </ul>

        <div class="tab-content" id="statusTabContent">
            @php
                $filterByProdi = fn($ta) => request('prodi')
                    ? $ta->mahasiswa->prodi === strtolower(request('prodi'))
                    : true;
            @endphp

            <div class="tab-pane fade show active" id="menunggu" role="tabpanel" aria-labelledby="menunggu-tab">
                @include('dosen.kaprodi.partials.table', [
                    'tugasAkhir' => $tugasAkhirMenunggu->filter($filterByProdi),
                ])
            </div>
            <div class="tab-pane fade" id="diterima" role="tabpanel" aria-labelledby="diterima-tab">
                @include('dosen.kaprodi.partials.table', [
                    'tugasAkhir' => $tugasAkhirDiterima->filter($filterByProdi),
                ])
            </div>
            <div class="tab-pane fade" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                @include('dosen.kaprodi.partials.table', [
                    'tugasAkhir' => $tugasAkhirDitolak->filter($filterByProdi),
                ])
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
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

                    <div id="wrapSimilar">
                        <hr>
                        <p><strong>Judul yang Mirip:</strong></p>
                        <ul id="modalSimilar" class="ms-3"></ul>
                    </div>

                    <div id="wrapDiterima" class="d-none mt-4">
                        <hr>
                        <p><strong>Disetujui Oleh:</strong> <span id="modalDisetujuiOleh"></span></p>
                        <p><strong>Tanggal Disetujui:</strong> <span id="modalTanggalDisetujui"></span></p>
                    </div>

                    <div id="wrapDitolak" class="d-none mt-4">
                        <hr>
                        <p><strong>Alasan Penolakan:</strong> <span id="modalAlasanTolak"></span></p>
                        <p><strong>Ditolak Oleh:</strong> <span id="modalDitolakOleh"></span></p>
                        <p><strong>Tanggal Ditolak:</strong> <span id="modalTanggalTolak"></span></p>
                    </div>

                    <form method="POST" id="formValidasi" action="">
                        @csrf
                        <div class="mt-4 d-flex justify-content-end" id="wrapActionButtons">
                            <button type="submit" class="btn btn-success me-2">Setujui</button>
                            <button type="button" class="btn btn-danger" id="btnTolak">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
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
                        <textarea name="alasan_penolakan" class="form-control" rows="4" required></textarea>
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

            document.getElementById('modalTolak').addEventListener('hidden.bs.modal', function() {
                modalDetailTA.show();
            });

            @if (session('alert'))
                swal({
                    title: "{{ session('alert.title') }}",
                    text: "{{ session('alert.message') }}",
                    icon: "{{ session('alert.type') }}",
                    button: "OK",
                });
            @endif
        });

        function showDetail(button) {
            const id = button.getAttribute('data-id');

            fetch(`/dosen/validasi/detail/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalNama').innerText = data.nama;
                    document.getElementById('modalNim').innerText = data.nim;
                    document.getElementById('modalProdi').innerText = data.prodi;
                    document.getElementById('modalJudul').innerText = data.judul;

                    // Similar titles
                    const similar = document.getElementById('modalSimilar');
                    similar.innerHTML = '';
                    if (data.similar && data.similar.length > 0) {
                        data.similar.forEach(j => {
                            const li = document.createElement('li');
                            li.textContent = j;
                            similar.appendChild(li);
                        });
                    } else {
                        similar.innerHTML = '<li class="text-muted">Tidak ditemukan judul serupa.</li>';
                    }

                    // Action buttons
                    const action = document.getElementById('wrapActionButtons');
                    action.classList.toggle('d-none', !data.actionable);

                    // Form action
                    document.getElementById('formValidasi').action = `/dosen/validasi/terima/${id}`;
                    document.getElementById('formTolak').action = `/dosen/validasi/tolak/${id}`;

                    // Info for accepted
                    const wrapAcc = document.getElementById('wrapDiterima');
                    wrapAcc.classList.toggle('d-none', !data.disetujui_oleh);
                    if (data.disetujui_oleh) {
                        document.getElementById('modalDisetujuiOleh').innerText = data.disetujui_oleh;
                        document.getElementById('modalTanggalDisetujui').innerText = data.tanggal_disetujui;
                    }

                    // Info for rejected
                    const wrapTolak = document.getElementById('wrapDitolak');
                    wrapTolak.classList.toggle('d-none', !data.alasan_penolakan);
                    if (data.alasan_penolakan) {
                        document.getElementById('modalAlasanTolak').innerText = data.alasan_penolakan;
                        document.getElementById('modalDitolakOleh').innerText = data.ditolak_oleh;
                        document.getElementById('modalTanggalTolak').innerText = data.tanggal_ditolak;
                    }

                    // Similar titles section toggle
                    document.getElementById('wrapSimilar').classList.toggle('d-none', !data.similar || data.similar
                        .length === 0);

                    modalDetailTA.show();

                    document.getElementById('btnTolak').onclick = () => {
                        modalDetailTA.hide();
                        setTimeout(() => modalTolakTA.show(), 300);
                    };
                })
                .catch(err => {
                    alert('Gagal memuat detail. Coba lagi.');
                    console.error(err);
                });
        }
    </script>
@endpush
