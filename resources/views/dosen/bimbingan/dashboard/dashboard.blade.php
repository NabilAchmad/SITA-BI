<!-- filepath: d:\SITA-BI\SITA-BI\resources\views\admin\bimbingan\dashboard\dashboard.blade.php -->
@extends('layouts.template.main')

@section('title', 'Dashboard Bimbingan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i> Daftar Jadwal Bimbingan</h5>
                <p class="text-muted mb-0">Daftar mahasiswa yang telah dijadwalkan bimbingan.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal Bimbingan</li>
                </ol>
            </nav>
        </div>

        {{-- Tabs Program Studi --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') == null ? 'active' : '' }}" href="?">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D4' ? 'active' : '' }}" href="?prodi=D4">D4</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('prodi') === 'D3' ? 'active' : '' }}" href="?prodi=D3">D3</a>
            </li>
        </ul>

        {{-- Form Cari Nama Mahasiswa --}}
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-2">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Program Studi</th>
                        <th scope="col">Judul Sidang</th>
                        <th scope="col">Dosen Pembimbing</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Waktu</th>
                        <th scope="col">Status Bimbingan</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tolak Bimbingan --}}
    <div class="modal fade" id="tolakBimbinganModal" tabindex="-1" aria-labelledby="tolakBimbinganModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('bimbingan.tolak') }}">
                @csrf
                <input type="hidden" name="bimbingan_id" id="bimbingan_id_input">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tolakBimbinganModalLabel">Komentar Penolakan Bimbingan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="komentar_penolakan" class="form-label">Alasan Penolakan</label>
                            <textarea name="komentar_penolakan" id="komentar_penolakan" class="form-control" rows="5" required></textarea>
                        </div>
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
    // Script untuk memasukkan ID ke dalam input tersembunyi saat tombol tolak diklik
    const modal = document.getElementById('tolakBimbinganModal');
    if(modal){
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            modal.querySelector('#bimbingan_id_input').value = id;
        });
    }
</script>
@endpush