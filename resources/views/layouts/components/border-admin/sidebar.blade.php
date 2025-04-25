<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/admin') }}"
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
                <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dashboard"
                        aria-expanded="{{ request()->is('admin') ? 'true' : 'false' }}"
                        class="{{ request()->is('admin') ? '' : 'collapsed' }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('admin') ? 'show' : '' }}" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('admin') ? 'active' : '' }}">
                                <a href="{{ url('/admin') }}">
                                    <span class="sub-item">Dashboard 1</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Akses Section -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Akses</h4>
                </li>

                <!-- Pengumuman -->
                <li class="nav-item {{ request()->is('pengumuman*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pengumuman"
                        class="{{ request()->is('pengumuman*') ? '' : 'collapsed' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('pengumuman*') ? 'show' : '' }}" id="pengumuman">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('pengumuman/create') ? 'active' : '' }}">
                                <a href="{{ url('/pengumuman/create') }}">
                                    <span class="sub-item">Buat Pengumuman</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('pengumuman/read') ? 'active' : '' }}">
                                <a href="{{ url('/pengumuman/read') }}">
                                    <span class="sub-item">Lihat Pengumuman</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li
                    class="nav-item {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#ta"
                        class="{{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Tugas Akhir</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'show' : '' }}"
                        id="ta">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('tugas-akhir/pilih-pembimbing') ? 'active' : '' }}">
                                <a href="{{ url('/tugas-akhir/pilih-pembimbing') }}">
                                    <span class="sub-item">Pilih Dosen Pembimbing</span>
                                </a>
                            </li>
                            <!-- Tambah menu lain nanti, misalnya Lihat Status, Histori Bimbingan, dll -->
                        </ul>
                    </div>
                </li>


                <!-- Berita Acara -->
                <li
                    class="nav-item {{ request()->is('berita-acara') || request()->is('berita-acara/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts"
                        class="{{ request()->is('berita-acara') || request()->is('berita-acara/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Berita Acara</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('berita-acara') || request()->is('berita-acara/*') ? 'show' : '' }}"
                        id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('berita-acara/create') ? 'active' : '' }}">
                                <a href="{{ url('/berita-acara/create') }}">
                                    <span class="sub-item">Buat Berita Acara</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('berita-acara/read') ? 'active' : '' }}">
                                <a href="{{ url('/berita-acara/read') }}">
                                    <span class="sub-item">Lihat Berita Acara</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('sidang') || request()->is('sidang/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms"
                        class="{{ request()->is('sidang') || request()->is('sidang/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('sidang') || request()->is('sidang/*') ? 'show' : '' }}"
                        id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('sidang/list-mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('/sidang/list-mahasiswa') }}">
                                    <span class="sub-item">Lihat Mahasiswa Sidang</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/sidang/lihat-jadwal') }}">
                                    <span class="sub-item">Lihat Jadwal Sidang</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Kelola Akun -->
                <li
                    class="nav-item {{ request()->is('kelola-akun') || request()->is('kelola-akun/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables"
                        class="{{ request()->is('kelola-akun') || request()->is('kelola-akun/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Kelola Akun</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('kelola-akun') || request()->is('kelola-akun/*') ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('kelola-akun/dosen') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/dosen') }}">
                                    <span class="sub-item">Dosen</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('kelola-akun/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/mahasiswa') }}">
                                    <span class="sub-item">Mahasiswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Laporan dan Statistik -->
                <li class="nav-item {{ request()->is('laporan') || request()->is('laporan/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#maps"
                        class="{{ request()->is('laporan') || request()->is('laporan/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Laporan dan Statistik</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('laporan') || request()->is('laporan/*') ? 'show' : '' }}"
                        id="maps">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('laporan/lihat') ? 'active' : '' }}">
                                <a href="{{ url('/laporan/lihat') }}">
                                    <span class="sub-item">Lihat Laporan dan Statistik</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Log dan Aktifitas -->
                <li class="nav-item {{ request()->is('logs') || request()->is('logs/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#logs"
                        class="{{ request()->is('logs') || request()->is('logs/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-terminal"></i>
                        <p>Log dan Aktifitas</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('logs') || request()->is('logs/*') ? 'show' : '' }}"
                        id="logs">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('logs/lihat') ? 'active' : '' }}">
                                <a href="{{ url('/logs/lihat') }}">
                                    <span class="sub-item">Lihat Log dan Aktifitas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
