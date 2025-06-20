<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ route('admin.dashboard') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <span class="fw-semibold d-none d-md-inline text-truncate text-white d-flex align-items-center"
                    style="max-width: 100%; white-space: nowrap; font-family: 'Poppins', sans-serif; font-size: 1.25rem; letter-spacing: 0.05em; transition: color 0.3s ease;">
                    <i class="fas fa-user-cog me-3 fs-4"></i> Admin Panel
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
                <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Laporan dan Statistik -->
                <li class="nav-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <a href="{{ route('laporan.statistik') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Laporan dan Statistik</p>
                    </a>
                </li>

                <!-- Log dan Aktifitas -->
                <li class="nav-item {{ request()->is('admin/logs*') ? 'active' : '' }}">
                    <a href="{{ route('log.aktifitas') }}">
                        <i class="fas fa-terminal"></i>
                        <p>Log dan Aktifitas</p>
                    </a>
                </li>

                <!-- Akses Section -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Akses</h4>
                </li>

                <!-- Kelola Akun -->
                <!-- Sidebar -->
                <li class="nav-item {{ request()->is('admin/kelola-akun*') ? 'active' : '' }}">
                    <a href="{{ route('akun-dosen.kelola') }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Kelola Akun</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
                    <a href="{{ route('penugasan-bimbingan.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Penugasan Bimbingan</p>
                    </a>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('admin/sidang*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard-sidang') }}" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                    </a>
                </li>

                <!-- Pengumuman -->
                <li class="nav-item {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                    <a href="{{ route('pengumuman.read') }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
