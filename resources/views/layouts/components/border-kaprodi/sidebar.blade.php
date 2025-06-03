<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ url('/kaprodi') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <!-- Icon Gear -->
                <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle flex-shrink-0 me-2"
                    style="width: 36px; height: 36px; transition: background-color 0.3s ease;">
                    <img src="{{ asset('assets/img/kaprodi/hat.svg') }}" alt="Kaprodi Icon"
                        style="width: 60%; height: 60%; filter: brightness(0) invert(1); transition: transform 0.3s ease;">
                </div>

                <!-- Teks -->
                <span class="fw-semibold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; transition: color 0.3s ease;">
                    Ketua Prodi
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
                <li class="nav-item {{ request()->is('ketua-prodi') ? 'active' : '' }}">
                    <a href="{{ route('kaprodi.dashboard') }}">
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

                {{-- Tugas Akhir --}}
                <li class="nav-item {{ request()->is('ketua-prodi/judulTA/*') || request()->is('kaprodi/judulTA') ? 'active' : '' }}">
                    <a href="{{ route('kaprodi.judul.page') }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Tugas Akhir</p>
                    </a>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('ketua-prodi/sidang/*') || request()->is('kaprodi/sidang/*') ? 'active' : '' }}">
                    <a href="{{ route('sidangDashboard.page') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                    </a>
                </li>

                <!-- Pengumuman -->
                <li class="nav-item {{ request()->is('ketua-prodi/pengumuman*') ? 'active' : '' }}">
                    <a href="{{ route('kaprodipengumuman.page') }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                    </a>
                </li>

                <!-- Laporan dan Statistik -->
                <li class="nav-item {{ request()->is('ketua-prodi/nilai/*') || request()->is('kaprodi/nilai/*') ? 'active' : '' }}">
                    <a href="{{ route('kaprodi.akhir.page') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Nilai Sidang</p>
                    </a>
                </li>

                <!-- Log dan Aktifitas -->
                <li class="nav-item {{ request()->is('kaprodi/logs') || request()->is('kaprodi/logs/*') ? 'active' : '' }}">
                    <a href="{{ url('/kaprodi/logs/lihat') }}">
                        <i class="fas fa-terminal"></i>
                        <p>Log dan Aktifitas</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
