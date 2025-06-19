@extends('layouts.template.mahasiswa')

@section('title', 'Dashboard Bimbingan')

@section('content')
    @if (!$tugasAkhir)
        <div class="position-relative overflow-hidden rounded-3 mb-4 p-4"
            style="background: linear-gradient(135deg, #fde3e3, #fea2a2); border-left: 5px solid #fd0d0d;">
            <div class="position-relative z-1">
                <h4 class="fw-bold text-danger mb-1">
                    <i class="bi bi-file-x-fill"></i> Anda Belum Memiliki Tugas Akhir
                </h4>
                <p class="text-dark mb-0">
                    Kelola seluruh kegiatan bimbingan Tugas Akhir Anda dengan dosen pembimbing.
                </p>
            </div>
            <i class="bi bi-mortarboard-fill text-danger position-absolute opacity-10"
                style="font-size: 7rem; right: 1.5rem; bottom: -1rem;"></i>
        </div>
    @else
        <div class="container-fluid py-4">
            <!-- Header -->
            <div class="position-relative overflow-hidden rounded-3 mb-4 p-4"
                style="background: linear-gradient(135deg, #e3f2fd, #f1f8ff); border-left: 5px solid #0d6efd;">
                <div class="position-relative z-1">
                    <h4 class="fw-bold text-primary mb-1">
                        <i class="bi bi-people-fill me-2"></i> Dashboard Bimbingan
                    </h4>
                    <p class="text-muted mb-0">Kelola seluruh kegiatan bimbingan Tugas Akhir Anda dengan dosen pembimbing.
                    </p>
                </div>
                <i class="bi bi-mortarboard-fill text-primary position-absolute opacity-10"
                    style="font-size: 7rem; right: 1.5rem; bottom: -1rem;"></i>
            </div>

            <!-- Jadwal Bimbingan -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0 text-primary fw-bold">
                            <i class="bi bi-calendar-check me-2"></i> Jadwal Bimbingan Saya
                        </h3>
                        <a href="{{ route('bimbingan.ajukanJadwal') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Baru
                        </a>
                    </div>

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @php
                        // Filter hanya jadwal aktif, tidak termasuk yang statusnya ditolak atau selesai
                        $jadwalAktif = $jadwals->filter(function ($item) {
                            return !in_array(strtolower($item->status_bimbingan), ['ditolak', 'selesai']);
                        });
                    @endphp

                    <div class="row g-4">
                        @forelse ($jadwalAktif as $jadwal)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-primary text-white py-3 rounded-top-4">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <h5 class="mb-0 fw-bold">
                                                <i class="bi bi-person-badge-fill me-2"></i>
                                                <span class="bg-white text-primary px-3 py-1 rounded-pill shadow-sm">
                                                    {{ $jadwal->dosen->user->name ?? 'Dosen tidak diketahui' }}
                                                </span>
                                            </h5>
                                            <span class="badge bg-white text-primary rounded-pill px-3 py-2 mt-2 mt-lg-0">
                                                <i class="bi bi-calendar-date me-1"></i>
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal_bimbingan)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <div class="mb-2">
                                                        <strong class="me-2">Status:</strong>
                                                        @php
                                                            $status = strtolower($jadwal->status_bimbingan);
                                                        @endphp
                                                        <span
                                                            class="badge rounded-pill px-3 py-2
                                                        @if ($status === 'diajukan') bg-warning text-dark
                                                        @elseif ($status === 'disetujui') bg-success
                                                        @elseif ($status === 'ditolak') bg-danger
                                                        @else bg-secondary @endif">
                                                            <i
                                                                class="bi
                                                            @if ($status === 'diajukan') bi-hourglass-split
                                                            @elseif ($status === 'disetujui') bi-check-circle
                                                            @elseif ($status === 'ditolak') bi-x-circle
                                                            @else bi-question-circle @endif me-1"></i>
                                                            {{ ucfirst($status ?? '-') }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <strong class="me-2">Waktu:</strong>
                                                        <span>
                                                            {{ $jadwal->jam_bimbingan ? date('H:i', strtotime($jadwal->jam_bimbingan)) . ' WIB' : 'Belum diatur' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <small class="text-muted">
                                                    <i class="bi bi-arrow-repeat me-1"></i>
                                                    Terakhir diperbarui:
                                                    {{ \Carbon\Carbon::parse($jadwal->updated_at)->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Catatan Bimbingan --}}
                                        @if ($jadwal->catatanBimbingan->count())
                                            <div class="bimbingan-notes mt-4">
                                                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                    <i class="bi bi-chat-left-text me-2"></i>Catatan Bimbingan
                                                </h6>
                                                <div class="timeline-notes position-relative ps-4">
                                                    @foreach ($jadwal->catatanBimbingan as $catatan)
                                                        <div class="timeline-item mb-4 position-relative">
                                                            <div class="timeline-badge
                                                            {{ $catatan->author_type === 'mahasiswa' ? 'bg-info' : 'bg-success' }}
                                                            position-absolute start-0 top-0 rounded-circle"
                                                                style="width: 15px; height: 15px;"></div>
                                                            <div class="card ms-4 shadow-sm border-0">
                                                                <div class="card-body p-3">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                                        <span
                                                                            class="fw-bold text-{{ $catatan->author_type === 'mahasiswa' ? 'info' : 'success' }}">
                                                                            {{ ucfirst($catatan->author_type) }}
                                                                        </span>
                                                                        <small class="text-muted">
                                                                            <i class="bi bi-clock me-1"></i>
                                                                            {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}
                                                                        </small>
                                                                    </div>
                                                                    <p class="mb-0">{{ $catatan->catatan }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-light border mt-4">
                                                <i class="bi bi-info-circle text-muted me-2"></i>
                                                Belum ada catatan bimbingan untuk jadwal ini.
                                            </div>
                                        @endif
                                    </div>

                                    <div
                                        class="card-footer bg-light d-flex justify-content-between align-items-center rounded-bottom-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalEditJadwal-{{ $jadwal->id }}">
                                            <i class="bi bi-pencil me-2"></i> Ajukan Perubahan Jadwal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card shadow-sm border-0 text-center py-5">
                                    <div class="card-body">
                                        <div class="empty-state-icon mb-4">
                                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5 class="text-muted mb-3">Belum ada jadwal bimbingan</h5>
                                        <p class="text-muted mb-4">Anda belum mengajukan jadwal bimbingan dengan dosen
                                            pembimbing</p>
                                        <a href="{{ route('bimbingan.ajukanJadwal') }}" class="btn btn-primary px-4">
                                            <i class="bi bi-plus-circle me-2"></i> Ajukan Jadwal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Jadwal -->
        @include('mahasiswa.Bimbingan.modal.edit', ['jadwals' => $jadwals])
        {{-- Bagian tampilan jadwal bimbingan --}}
        {{-- ... seluruh card yang kamu tulis tetap disini --}}
    @endif

@endsection

@push('scripts')
    @if (session('success'))
        <script>
            swal({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-primary"
                    }
                }
            });
        </script>
    @endif
@endpush

@push('styles')
    <style>
        .opacity-10 {
            opacity: 0.1;
        }

        .icon.icon-shape {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-hover:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        .transition-scale {
            transition: transform 0.3s ease-in-out;
        }

        .transition-scale:hover {
            transform: scale(1.03);
        }

        .timeline-notes::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            padding-left: 1rem;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .empty-state-icon {
            opacity: 0.7;
        }
    </style>
@endpush
