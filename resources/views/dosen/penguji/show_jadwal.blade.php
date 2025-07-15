@extends('layouts.template.main')

@section('title', 'Detail & Penilaian Sidang')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Detail & Penilaian Sidang</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('dosen/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dosen.penguji.index') }}">Sidang Ujian</a></li>
                            <li class="breadcrumb-item active">Detail Penilaian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- KOLOM KIRI: INFORMASI SIDANG --}}
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-info-circle fs-5 me-2"></i>
                        <h5 class="card-title mb-0">Informasi Sidang</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row g-3">
                            <dt class="col-sm-4">Nama Mahasiswa</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $sidang->tugasAkhir?->mahasiswa?->user?->name ?? '-' }}</dd>

                            <dt class="col-sm-4">NIM</dt>
                            <dd class="col-sm-8">{{ $sidang->tugasAkhir?->mahasiswa?->nim ?? '-' }}</dd>

                            <hr class="my-2">

                            <dt class="col-sm-12">Judul Tugas Akhir</dt>
                            <dd class="col-sm-12">{{ $sidang->tugasAkhir?->judul ?? '-' }}</dd>

                            <hr class="my-2">

                            <dt class="col-sm-4">Tanggal Sidang</dt>
                            <dd class="col-sm-8">
                                {{ $sidang->jadwal?->tanggal ? \Carbon\Carbon::parse($sidang->jadwal->tanggal)->translatedFormat('l, d F Y') : '-' }}
                            </dd>

                            <dt class="col-sm-4">Waktu</dt>
                            <dd class="col-sm-8">
                                {{ $sidang->jadwal?->waktu_mulai ? \Carbon\Carbon::parse($sidang->jadwal->waktu_mulai)->format('H:i') : '' }}
                                -
                                {{ $sidang->jadwal?->waktu_selesai ? \Carbon\Carbon::parse($sidang->jadwal->waktu_selesai)->format('H:i') : '' }}
                                WIB</dd>

                            <dt class="col-sm-4">Ruangan</dt>
                            <dd class="col-sm-8">{{ $sidang->jadwal?->ruangan?->nama_ruangan ?? 'Belum ditentukan' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM INPUT NILAI --}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-pencil-square fs-5 me-2"></i>
                        <h5 class="card-title mb-0">Formulir Penilaian</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Silakan masukkan nilai ujian pada skala 0-100.</p>

                        {{-- Form akan di-POST ke route yang akan kita buat nanti --}}
                        <form action="{{ route('dosen.penguji.store_nilai', $sidang->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nilai" class="form-label fw-semibold">Nilai Ujian</label>
                                <input type="number" class="form-control form-control-lg" id="nilai" name="nilai"
                                    min="0" max="100" placeholder="Contoh: 85" required>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label fw-semibold">Catatan / Revisi (Opsional)</label>
                                <textarea class="form-control" name="catatan" id="catatan" rows="5"
                                    placeholder="Tuliskan catatan atau poin-poin revisi untuk mahasiswa..."></textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
