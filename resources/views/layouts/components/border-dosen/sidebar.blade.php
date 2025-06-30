@php
    $user = auth()->user();
    $userRoles = $user?->roles->pluck('nama_role')->toArray() ?? [];
    // PERBAIKAN: Menggunakan nama relasi yang benar 'peranDosenTa' sesuai definisi di Model Dosen.
    $dosenRoles = $user?->dosen?->peranDosenTa?->pluck('peran')->unique()->toArray() ?? [];
@endphp

<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ url('/dosen/dashboard') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100">
                <div class="d-flex align-items-center justify-content-center bg-light bg-opacity-25 rounded-circle flex-shrink-0 me-2 shadow-sm"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-person-workspace fs-4 text-white"></i>
                </div>
                <span class="fw-bold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; letter-spacing: 0.5px; font-size: 1.1rem;">
                    @if (in_array('kajur', $userRoles))
                        Kajur
                    @elseif (in_array('kaprodi', $userRoles))
                        Kaprodi
                    @else
                        Dosen
                    @endif
                </span>
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('dosen/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dosen/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Akses Section -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Akses</h4>
                </li>

                <!-- Bimbingan: Hanya untuk Pembimbing -->
                @if (collect($dosenRoles)->contains(function ($val) {
                        return in_array($val, ['pembimbing1', 'pembimbing2']);
                    }))
                    <li class="nav-item {{ request()->is('dosen/bimbingan*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.bimbingan.index') }}" class="nav-link">
                            <i class="fas fa-users-cog me-2"></i>
                            <p>Bimbingan</p>
                        </a>
                    </li>
                @endif

                <!-- Tawaran Topik: Semua dosen -->
                <li class="nav-item {{ request()->is('dosen/tawaran-topik*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.tawaran-topik.index') }}" class="nav-link">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <p>Tawaran Topik</p>
                    </a>
                </li>

                <!-- Sidang: kaprodi, kajur, penguji -->
                @if (in_array('kaprodi', $userRoles) ||
                        in_array('kajur', $userRoles) ||
                        collect($dosenRoles)->contains(fn($val) => str_starts_with($val, 'penguji')))
                    <li class="nav-item {{ request()->is('dosen/sidang*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.sidang.index') }}" class="nav-link">
                            <i class="fas fa-calendar-check me-2"></i>
                            <p>Sidang Mahasiswa</p>
                        </a>
                    </li>
                @endif

                <!-- Validasi Judul: Hanya untuk Kaprodi -->
                @if (in_array('kaprodi', $userRoles))
                    <li class="nav-item {{ request()->is('dosen/validasi*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.validasi-tugas-akhir.index') }}" class="nav-link">
                            <i class="fas fa-check-circle me-2"></i>
                            <p>Validasi Judul Tugas Akhir</p>
                        </a>
                    </li>
                @endif

                <!-- Validasi Nilai TA: Hanya untuk Kajur -->
                @if (in_array('kajur', $userRoles))
                    <li class="nav-item {{ request()->is('dosen/validasi-nilai-tugas-akhir*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.validasi-nilai-tugas-akhir.index') }}" class="nav-link">
                            <i class="fas fa-check-circle me-2"></i>
                            <p>Validasi Nilai Tugas Akhir</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
