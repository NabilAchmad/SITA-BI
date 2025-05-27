<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/mahasiswa') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <!-- Icon Gear -->
                <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle flex-shrink-0 me-2"
                    style="width: 36px; height: 36px; transition: background-color 0.3s ease;">
                    <img src="{{ asset('assets/img/mahasiswa/gear.svg') }}" alt="mahasiswa Icon"
                        style="width: 60%; height: 60%; filter: brightness(0) invert(1); transition: transform 0.3s ease;">
                </div>

                <!-- Teks -->
                <span class="fw-semibold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; transition: color 0.3s ease;">
                    Mahasiswa
                </span>
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('mahasiswa') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Akses Section -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Akses</h4>
                </li>

                <!-- Tugas Akhir -->
                <li class="nav-item {{ request()->is('mahasiswa/tugas-akhir') || request()->is('mahasiswa/tugas-akhir/*') ? 'active' : ''  }}">
                    <a href="{{ route('tugas-akhir.dashboard') }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Dashboard Tugas Akhir</p>
                    </a>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('mahasiswa/sidang') || request()->is('mahasiswa/sidang/*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.sidang') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                    </a>
                </li>

                <!-- Bimbingan -->
                <li class="nav-item {{ request()->is('mahasiswa/bimbingan') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.bimbingan') }}" class="nav-link">
                        <i class="fas fa-users-cog me-2"></i>
                        <p>Bimbingan</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
