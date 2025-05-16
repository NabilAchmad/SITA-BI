<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <!-- Icon Gear -->
                <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle flex-shrink-0 me-2"
                    style="width: 36px; height: 36px; transition: background-color 0.3s ease;">
                    <img src="{{ asset('assets/img/admin/gear.svg') }}" alt="Admin Icon"
                        style="width: 60%; height: 60%; filter: brightness(0) invert(1); transition: transform 0.3s ease;">
                </div>

                <!-- Teks -->
                <span class="fw-semibold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; transition: color 0.3s ease;">
                    Admin Panel
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

                <!-- Berita Acara -->
                <li class="nav-item {{ request()->is('admin/berita-acara*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts"
                        class="{{ request()->is('admin/berita-acara*') ? '' : 'collapsed' }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Berita Acara</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin/berita-acara*') ? 'show' : '' }}" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin/berita-acara/create') ? 'active' : '' }}">
                                <a href="{{ route('berita-acara.create') }}">
                                    <span class="sub-item">Buat Berita Acara</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/berita-acara/read') ? 'active' : '' }}">
                                <a href="{{ route('berita-acara.read') }}">
                                    <span class="sub-item">Lihat Berita Acara</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Kelola Akun -->
                <li class="nav-item {{ request()->is('admin/kelola-akun*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables"
                        class="{{ request()->is('admin/kelola-akun*') ? '' : 'collapsed' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Kelola Akun</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin/kelola-akun*') ? 'show' : '' }}" id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin/kelola-akun/dosen') ? 'active' : '' }}">
                                <a href="{{ route('akun-dosen.kelola') }}">
                                    <span class="sub-item">Dosen</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/kelola-akun/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ route('akun-mahasiswa.kelola') }}">
                                    <span class="sub-item">Mahasiswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Mahasiswa -->
                <li class="nav-item {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#ta"
                        class="{{ request()->is('admin/mahasiswa*') ? '' : 'collapsed' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Mahasiswa</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin/mahasiswa*') ? 'show' : '' }}" id="ta">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin/mahasiswa/belum-pembimbing') ? 'active' : '' }}">
                                <a href="{{ route('penugasan-bimbingan.index') }}">
                                    <span class="sub-item">Assign Dosen Pembimbing</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/mahasiswa/list-mahasiswa') ? 'active' : '' }}">
                                <a href="{{ route('list-mahasiswa') }}">
                                    <span class="sub-item">List Mahasiswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Pengumuman -->
                <li class="nav-item {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pengumuman"
                        class="{{ request()->is('admin/pengumuman*') ? '' : 'collapsed' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin/pengumuman*') ? 'show' : '' }}" id="pengumuman">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin/pengumuman/read') ? 'active' : '' }}">
                                <a href="{{ route('pengumuman.read') }}">
                                    <span class="sub-item">Lihat Pengumuman</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/pengumuman/trash') ? 'active' : '' }}">
                                <a href="{{ route('pengumuman.trashed') }}">
                                    <span class="sub-item">Pengumuman Dihapus</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('admin/sidang*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms"
                        class="{{ request()->is('admin/sidang*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin/sidang*') ? 'show' : '' }}" id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin/sidang/list-mahasiswa') ? 'active' : '' }}">
                                <a href="{{ route('mahasiswa-sidang.read') }}">
                                    <span class="sub-item">Lihat Mahasiswa Sidang</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ route('jadwal-sidang.read') }}">
                                    <span class="sub-item">Lihat Jadwal Sidang</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
