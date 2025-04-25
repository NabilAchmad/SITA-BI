<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/ketua-prodi') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <!-- Icon Gear -->
                <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle flex-shrink-0 me-2"
                    style="width: 36px; height: 36px; transition: background-color 0.3s ease;">
                    <img src="{{ asset('assets/img/ketua-prodi/hat.svg') }}" alt="Ketua Jurusan Icon"
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
                    <a data-bs-toggle="collapse" href="#dashboard"
                        aria-expanded="{{ request()->is('ketua-prodi') ? 'true' : 'false' }}"
                        class="{{ request()->is('ketua-prodi') ? '' : 'collapsed' }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('ketua-prodi') ? 'show' : '' }}" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('ketua-prodi') ? 'active' : '' }}">
                                <a href="{{ url('/ketua-prodi') }}">
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

                {{-- Tugas Akhir --}}
                <li
                    class="nav-item {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables"
                        class="{{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Tugas Akhir</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('kaprodi/judulTA/ACCJudulTA') ? 'active' : '' }}">
                                <a href="{{ route('accjudul.page') }}">
                                    <span class="sub-item">Judul Tugas Akhir</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('ketua-prodi/sidang') || request()->is('ketua-prodi/sidang/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms"
                        class="{{ request()->is('ketua-prodi/sidang') || request()->is('ketua-prodi/sidang/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('sidang') || request()->is('ketua-prodi/sidang/*') ? 'show' : '' }}"
                        id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('ketua-prodi/sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('ketua-prodi/sidang/lihat-jadwal') }}">
                                    <span class="sub-item">Lihat Jadwal Sidang</span></a>
                            </li>

                            <li class="{{ request()->is('ketua-prodi/sidang/lihat-mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('ketua-prodi/sidang/lihat-mahasiswa') }}">
                                    <span class="sub-item">Mahasiswa Sidang</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Pengumuman -->
                <li class="nav-item {{ request()->is('ketua-prodi/pengumuman*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pengumuman"
                        class="{{ request()->is('ketua-prodi/pengumuman*') ? '' : 'collapsed' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('ketua-prodi/pengumuman*') ? 'show' : '' }}" id="pengumuman">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('ketua-prodi/pengumuman/lihat-pengumuman') ? 'active' : '' }}">
                                <a href="{{ url('ketua-prodi/pengumuman/lihat-pengumuman') }}">
                                    <span class="sub-item">Lihat Pengumuman</span>
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
