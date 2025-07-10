@extends('layouts.template.main')

@section('title', 'Detail Mahasiswa Bimbingan')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-gradient">
                    <i class="bi bi-person-badge me-2"></i>Detail Mahasiswa Bimbingan
                </h2>
            </div>
        </div>

        {{-- Informasi Mahasiswa --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('storage/' . ($mahasiswa->user->photo ?? 'default-avatar.jpg')) }}"
                            class="rounded-circle shadow-sm img-fluid"
                            style="width: 100px; height: 100px; object-fit: cover;" alt="Foto Mahasiswa">
                    </div>
                    <div class="col-md-5">
                        <h4 class="fw-bold mb-0">{{ $mahasiswa->user->name }}</h4>
                        <span class="badge bg-primary bg-opacity-10 text-white mt-1">{{ $mahasiswa->nim }}</span>
                        <ul class="list-unstyled mt-3">
                            <li><i class="bi bi-building me-2 text-black"></i><strong>Prodi:</strong>
                                {{ strtoupper($mahasiswa->prodi) }}</li>
                            <li><i class="bi bi-calendar me-2 text-black"></i><strong>Angkatan:</strong>
                                {{ $mahasiswa->angkatan }}</li>
                            <li><i class="bi bi-person-check me-2 text-black"></i><strong>Status:</strong>
                                {{ ucfirst($mahasiswa->status) }}</li>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <div class="bg-light p-3 rounded-3 h-100">
                            <h6 class="fw-bold mb-3">Kontak Mahasiswa</h6>
                            <p class="mb-1"><i
                                    class="bi bi-envelope me-2 text-muted"></i>{{ $mahasiswa->user->email ?? '-' }}</p>
                            <p class="mb-1"><i
                                    class="bi bi-telephone me-2 text-muted"></i>{{ $mahasiswa->no_telepon ?? '-' }}</p>
                            <p class="mb-0"><i class="bi bi-house me-2 text-muted"></i>{{ $mahasiswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Tugas Akhir --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-secondary mb-0">
                            <i class="bi bi-journal-bookmark me-2"></i>Progres Tugas Akhir
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $tugasAkhir->judul ?? 'Judul Belum Ditentukan' }}</h4>
                        <span class="badge bg-info bg-opacity-10 text-white mb-3 py-2 px-3 rounded-pill">
                            {{ ucfirst(str_replace('_', ' ', $tugasAkhir->status)) }}
                        </span>
                        <p class="mb-2"><strong>Tanggal Pengajuan:</strong> {{ $tugasAkhir->tanggal_pengajuan ?? '-' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Dokumen Proposal</h6>
                            @if ($tugasAkhir->file_path)
                                <a href="{{ asset('storage/' . $tugasAkhir->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-download me-1"></i> Unduh Proposal
                                </a>
                            @else
                                <span class="badge bg-light text-muted py-2 px-3">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pengajuan perubahan jadwal --}}
                @if ($bimbinganList)
                    <div class="card shadow-sm border-0 rounded-4 mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="bi bi-calendar-range me-2"></i>Pengajuan Perubahan Jadwal Bimbingan
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $pengajuanList = $bimbinganList->flatMap(function ($bimbingan) {
                                    return $bimbingan->historyPerubahan->where('status', 'menunggu');
                                });
                            @endphp

                            @forelse ($pengajuanList as $perubahan)
                                <div class="mb-4 p-3 border rounded shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">Bimbingan tanggal: {{ $perubahan->tanggal_lama }}
                                                {{ $perubahan->jam_lama }}</h6>
                                            <small class="text-muted">Diajukan:
                                                {{ $perubahan->created_at->format('d M Y, H:i') }}</small>
                                        </div>
                                        <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong class="text-danger">Jadwal Sebelumnya:</strong>
                                            <div>{{ $perubahan->tanggal_lama }} - {{ $perubahan->jam_lama }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong class="text-success">Jadwal Diajukan:</strong>
                                            <div>{{ $perubahan->tanggal_baru }} - {{ $perubahan->jam_baru }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <strong>Alasan Perubahan:</strong>
                                        <div class="bg-light p-2 rounded">
                                            {{ $perubahan->alasan_perubahan ?: 'Tidak ada alasan' }}</div>
                                    </div>

                                    <div class="mt-3 d-flex gap-2">
                                        <form action="{{ route('jadwal.terima', $perubahan->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Setujui
                                            </button>
                                        </form>

                                        <!-- Tombol untuk tolak dengan modal -->
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#modalTolak-{{ $perubahan->id }}">
                                            <i class="bi bi-x-circle me-1"></i> Tolak
                                        </button>
                                    </div>

                                    <!-- Modal Tolak -->
                                    <div class="modal fade" id="modalTolak-{{ $perubahan->id }}" tabindex="-1"
                                        aria-labelledby="modalTolakLabel-{{ $perubahan->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('jadwal.tolak', $perubahan->id) }}" method="POST"
                                                class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pengajuan Jadwal</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="komentar-{{ $perubahan->id }}"
                                                            class="form-label">Alasan Penolakan</label>
                                                        <textarea name="komentar" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox text-muted fs-2"></i>
                                    <p class="text-muted">Tidak ada pengajuan perubahan jadwal.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                {{-- Riwayat Revisi --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-warning mb-0">
                            <i class="bi bi-clipboard2-pulse me-2"></i>Riwayat Revisi
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse ($revisiList as $revisi)
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-0">Revisi ke-{{ $loop->iteration }}</h6>
                                    <small class="text-muted">{{ $revisi->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <div class="bg-light-warning p-3 rounded mt-2">
                                    <p class="mb-2">{{ $revisi->deskripsi }}</p>
                                    @if ($revisi->file_path)
                                        <a href="{{ asset('storage/' . $revisi->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-warning rounded-pill">
                                            <i class="bi bi-download me-1"></i> Unduh Revisi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                <p class="text-muted">Tidak ada revisi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Riwayat Bimbingan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-success mb-0">
                            <i class="bi bi-calendar2-check me-2"></i>Riwayat Bimbingan
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse ($bimbinganList as $bimbingan)
                            <div class="mb-4 p-3 border rounded shadow-sm bg-white">
                                <h6 class="fw-bold text-dark mb-1">
                                    {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d M Y') }} •
                                    {{ $bimbingan->jam_bimbingan }}
                                </h6>

                                <span
                                    class="badge mb-2
                        @if ($bimbingan->status_bimbingan === 'disetujui') bg-success
                        @elseif($bimbingan->status_bimbingan === 'ditolak') bg-danger
                        @elseif($bimbingan->status_bimbingan === 'selesai') bg-primary
                        @else bg-warning text-dark @endif">
                                    {{ ucfirst($bimbingan->status_bimbingan) }}
                                </span>

                                {{-- Indikasi perubahan jadwal --}}
                                @if ($bimbingan->pengajuan_perubahan_jadwal)
                                    <div class="alert alert-warning py-2 small d-flex align-items-center">
                                        <i class="bi bi-clock-history me-2"></i>
                                        Mahasiswa mengajukan perubahan jadwal:
                                        <strong class="ms-1">{{ $bimbingan->pengajuan_perubahan_jadwal->tanggal_baru }}
                                            {{ $bimbingan->pengajuan_perubahan_jadwal->jam_baru }}</strong>
                                    </div>
                                @endif

                                {{-- Catatan Bimbingan --}}
                                @if ($bimbingan->catatanBimbingan->isNotEmpty())
                                    @foreach ($bimbingan->catatanBimbingan as $catatan)
                                        <div class="mb-2 p-2 bg-light rounded">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-chat-left-dots me-1"></i>
                                                <strong>{{ ucfirst($catatan->author_type) }}</strong> pada
                                                {{ $catatan->created_at->format('d M Y, H:i') }}
                                            </small>
                                            <p class="mb-0">{{ $catatan->catatan }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="mb-2 text-muted">Tidak ada catatan</p>
                                @endif

                                @php
                                    $adaPengajuanPerubahan =
                                        $bimbingan->historyPerubahan->where('status', 'menunggu')->count() > 0;
                                @endphp

                                {{-- Tombol Aksi Dosen --}}
                                @if (auth()->user()->roles('dosen') && $bimbingan->status_bimbingan === 'diajukan')
                                    <div class="mt-2 d-flex gap-2">
                                        @if ($adaPengajuanPerubahan)
                                            <button type="button"
                                                class="btn btn-sm btn-success rounded-pill trigger-alert">
                                                <i class="bi bi-check-circle me-1"></i> Setujui
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-danger rounded-pill trigger-alert">
                                                <i class="bi bi-x-circle me-1"></i> Tolak
                                            </button>
                                        @else
                                            <form action="{{ route('bimbingan.setujui', $bimbingan->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i> Setujui
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTolakBimbingan-{{ $bimbingan->id }}">
                                                <i class="bi bi-x-circle me-1"></i> Tolak
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                {{-- Modal Tolak --}}
                                @include('dosen.bimbingan.modal.tolak-bimbingan', [
                                    'bimbingan' => $bimbingan,
                                ])

                                <div class="d-flex gap-2 mt-3">
                                    {{-- Tombol Download Dokumen --}}
                                    @if ($bimbingan->status_bimbingan === 'disetujui' && $bimbingan->tugasAkhir->file_path)
                                        <a href="{{ asset('storage/' . $bimbingan->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success rounded-pill">
                                            <i class="bi bi-download me-1"></i> Dokumen Bimbingan
                                        </a>
                                    @endif

                                    {{-- Tombol Tandai Selesai --}}
                                    @if (auth()->user()->roles('dosen') && $bimbingan->status_bimbingan === 'disetujui')
                                        <form action="{{ route('bimbingan.selesai', $bimbingan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill">
                                                <i class="bi bi-check2-square me-1"></i> Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted fs-2"></i>
                                <p class="text-muted">Belum ada bimbingan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold text-warning mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Pengajuan Pembatalan Tugas Akhir
                    </h5>
                </div>
                <div class="card-body">
                    @if ($tugasAkhir->status === 'menunggu_pembatalan')
                        <div class="mb-4 p-3 border rounded shadow-sm bg-white">
                            <h6 class="fw-bold text-dark mb-3">Data Pengajuan:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 border-0">
                                    <i class="bi bi-person me-2 text-secondary"></i>
                                    <strong>Nama:</strong> {{ $mahasiswa->user->name }}
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <i class="bi bi-journal-text me-2 text-secondary"></i>
                                    <strong>Judul TA:</strong> {{ $tugasAkhir->judul }}
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <i class="bi bi-clock me-2 text-secondary"></i>
                                    <strong>Diajukan pada:</strong> {{ $tugasAkhir->updated_at->format('d M Y, H:i') }}
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <i class="bi bi-clock me-2 text-secondary"></i>
                                    <strong>Alasan Pembatalan:</strong> {{ $tugasAkhir->alasan_pembatalan }}
                                </li>
                            </ul>

                            <div class="mt-3 d-flex gap-2">
                                <form action="{{ route('setuju-pembatalan-tugas-akhir', $tugasAkhir->id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Setujui Pembatalan
                                    </button>
                                </form>
                                <form action="{{ route('tolak-pembatalan-tugas-akhir', $tugasAkhir->id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i> Tolak Pembatalan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check2-circle text-muted fs-2"></i>
                            <p class="text-muted">Tidak ada pengajuan pembatalan tugas akhir.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertButtons = document.querySelectorAll('.trigger-alert');
            alertButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    swal({
                        title: "Pengajuan Jadwal Masih Diproses",
                        text: "Silakan selesaikan pengajuan perubahan jadwal terlebih dahulu.",
                        icon: "warning",
                        buttons: {
                            confirm: {
                                text: "OK",
                                className: "btn btn-warning"
                            }
                        }
                    });
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .text-gradient {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endpush
