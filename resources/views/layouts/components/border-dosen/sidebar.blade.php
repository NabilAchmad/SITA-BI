<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ url('/dosen/dashboard') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">
                <!-- Bootstrap Icon Topi -->
                <div class="d-flex align-items-center justify-content-center bg-light bg-opacity-25 rounded-circle flex-shrink-0 me-2 shadow-sm"
                    style="width: 40px; height: 40px; transition: background-color 0.3s;">
                    <i class="bi bi-person-workspace fs-4 text-white"></i>
                </div>
                <!-- Teks -->
                <span class="fw-bold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; letter-spacing: 0.5px; font-size: 1.1rem;">
                    Dosen
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
                <li class="nav-item {{ request()->is('dosen/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dosen/dashboard') }}">
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

                <!-- Bimbingan -->
                <li
                    class="nav-item {{ request()->is('dosen/bimbingan') || request()->is('dosen/bimbingan/*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.bimbingan.index') }}" class="nav-link">
                        <i class="fas fa-users-cog me-2"></i>
                        <p>Bimbingan</p>
                    </a>
                </li>

                <!-- Tawaran Topik -->
                <li
                    class="nav-item {{ request()->is('dosen/tawaran-topik') || request()->is('dosen/tawaran-topik/*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.tawaran-topik.index') }}" class="nav-link">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <p>Tawaran Topik</p>
                    </a>
                </li>

                <!-- Sidang -->
                <li
                    class="nav-item {{ request()->is('dosen/sidang') || request()->is('dosen/sidang/*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.sidang.index') }}" class="nav-link">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        <p>Sidang</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
