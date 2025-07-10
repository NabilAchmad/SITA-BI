<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark2">

            @php
                $user = auth()->user();
                $dashboardRoute = route('home'); // Rute default
                $panelName = 'SITA-BI';
                $iconClass = 'bi bi-app-indicator'; // Ikon default

                if ($user) {
                    // Cek peran dari yang paling spesifik ke yang paling umum
                    if ($user->hasRole('admin')) {
                        $dashboardRoute = route('admin.dashboard');
                        $panelName = 'Admin Panel';
                        $iconClass = 'fas fa-user-cog';
                    } elseif ($user->hasRole('kajur')) {
                        $dashboardRoute = route('dosen.dashboard');
                        $panelName = 'Ketua Jurusan';
                        $iconClass = 'bi bi-person-workspace';
                    } elseif ($user->hasRole('kaprodi-d3')) {
                        $dashboardRoute = route('dosen.dashboard');
                        $panelName = 'Kaprodi D3';
                        $iconClass = 'bi bi-person-workspace';
                    } elseif ($user->hasRole('kaprodi-d4')) {
                        $dashboardRoute = route('dosen.dashboard');
                        $panelName = 'Kaprodi D4';
                        $iconClass = 'bi bi-person-workspace';
                    } elseif ($user->hasRole('dosen')) {
                        $dashboardRoute = route('dosen.dashboard');
                        $panelName = 'Dosen Panel';
                        $iconClass = 'bi bi-person-workspace';
                    } elseif ($user->hasRole('mahasiswa')) {
                        $dashboardRoute = route('mahasiswa.dashboard');
                        $panelName = 'Mahasiswa Panel';
                        $iconClass = 'bi bi-mortarboard-fill';
                    }
                }
            @endphp

            <a href="{{ $dashboardRoute }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100">
                <div class="d-flex align-items-center justify-content-center bg-light bg-opacity-25 rounded-circle flex-shrink-0 me-2 shadow-sm"
                    style="width: 40px; height: 40px;">
                    <i class="{{ $iconClass }} fs-4 text-white"></i>
                </div>
                <span class="fw-bold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; letter-spacing: 0.5px; font-size: 1.1rem;">
                    {{ $panelName }}
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

                {{-- Dashboard Utama --}}
                <li
                    class="nav-item {{ request()->is(str_replace(url('/'), '', $dashboardRoute) . '*') ? 'active' : '' }}">
                    <a href="{{ $dashboardRoute }}"><i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- ====================================================== --}}
                {{-- MENU MANAJEMEN SISTEM --}}
                {{-- ====================================================== --}}
                @canany(['manage user accounts', 'manage pengumuman', 'view logs'])
                    <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Manajemen Sistem</h4>
                    </li>
                    @can('manage user accounts')
                        <li class="nav-item {{ request()->is('admin/kelola-akun*') ? 'active' : '' }}">
                            <a href="{{ route('admin.akun.dosen.index') }}"><i class="fas fa-users-cog"></i>
                                <p>Kelola Akun</p>
                            </a>
                        </li>
                    @endcan
                    @can('manage pengumuman')
                        <li class="nav-item {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengumuman.index') }}"><i class="fas fa-bullhorn"></i>
                                <p>Pengumuman</p>
                            </a>
                        </li>
                    @endcan
                @endcanany

                {{-- ====================================================== --}}
                {{-- MENU DOSEN (Asumsi tidak ada permission spesifik) --}}
                {{-- ====================================================== --}}
                @if (auth()->user()->hasAnyRole(['dosen', 'kajur', 'kaprodi-d3', 'kaprodi-d4']))
                    <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Aktivitas Dosen</h4>
                    </li>
                    <li class="nav-item {{ request()->is('dosen/bimbingan-mahasiswa*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.bimbingan.index') }}"><i class="fas fa-chalkboard-teacher"></i>
                            <p>Bimbingan Mahasiswa</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('dosen/tawaran-topik*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.tawaran-topik.index') }}"><i class="fas fa-clipboard-list"></i>
                            <p>Tawaran Topik</p>
                        </a>
                    </li>
                @endif


                {{-- ====================================================== --}}
                {{-- MENU MANAJEMEN JURUSAN --}}
                {{-- ====================================================== --}}
                @canany(['full access penugasan pembimbing', 'manage sidang'])
                    <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Manajemen Jurusan</h4>
                    </li>
                    <li class="nav-item {{ request()->is('jurusan/validasi-judul*') ? 'active' : '' }}">
                        <a href="{{ route('jurusan.validasi-judul.index') }}"><i class="fas fa-check-circle"></i>
                            <p>Validasi Judul TA</p>
                        </a>
                    </li>
                    @can('full access penugasan pembimbing')
                        <li class="nav-item {{ request()->is('jurusan/penugasan-pembimbing*') ? 'active' : '' }}">
                            <a href="{{ route('jurusan.penugasan-pembimbing.index') }}"><i class="fas fa-user-graduate"></i>
                                <p>Penugasan Pembimbing</p>
                            </a>
                        </li>
                    @endcan
                    @can('manage sidang')
                        <li class="nav-item {{ request()->is('jurusan/penjadwalan-sidang*') ? 'active' : '' }}">
                            <a href="{{ route('jurusan.penjadwalan-sidang.index') }}"><i class="fas fa-calendar-alt"></i>
                                <p>Penjadwalan Sidang</p>
                            </a>
                        </li>
                    @endcan
                @endcanany

                {{-- ====================================================== --}}
                {{-- MENU MAHASISWA --}}
                {{-- ====================================================== --}}
                @if (auth()->user()->hasRole('mahasiswa'))
                    <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Proses Akademik</h4>
                    </li>
                    <li class="nav-item {{ request()->is('mahasiswa/tugas-akhir*') ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.tugas-akhir.dashboard') }}"><i class="fas fa-file-signature"></i>
                            <p>Tugas Akhir</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('mahasiswa/bimbingan*') ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.bimbingan.dashboard') }}"><i class="fas fa-users"></i>
                            <p>Bimbingan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('mahasiswa/sidang*') ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.sidang.dashboard') }}"><i class="fas fa-gavel"></i>
                            <p>Sidang</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
