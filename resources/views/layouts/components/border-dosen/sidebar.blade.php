<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/dosen') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100"
                style="max-width: 100%; overflow: hidden; transition: background-color 0.3s ease;">

                <!-- Icon Gear -->
                <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle flex-shrink-0 me-2"
                    style="width: 36px; height: 36px; transition: background-color 0.3s ease;">
                    <img src="{{ asset('assets/img/dosen/hat.svg') }}" alt="Ketua Jurusan Icon"
                        style="width: 60%; height: 60%; filter: brightness(0) invert(1); transition: transform 0.3s ease;">
                </div>

                <!-- Teks -->
                <span class="fw-semibold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; transition: color 0.3s ease;">
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
                <li class="nav-item {{ request()->is('dosen') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dashboard"
                        aria-expanded="{{ request()->is('dosen') ? 'true' : 'false' }}"
                        class="{{ request()->is('dosen') ? '' : 'collapsed' }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('dosen') ? 'show' : '' }}" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('dosen') ? 'active' : '' }}">
                                <a href="{{ route('dosen.dashboard') }}">
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

                <!-- jadwal -->
                <li class="nav-item {{ request()->is('dosen/jadwal') || request()->is('dosen/jadwal/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms"
                        class="{{ request()->is('dosen/jadwal') || request()->is('dosen/jadwal/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Bimbingan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('jadwal') || request()->is('dosen/jadwal/*') ? 'show' : '' }}"
                        id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('dosen/jadwal/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ route('dosen.jadwal.read') }}">
                                    <span class="sub-item">List Bimbingan</span></a>
                            </li>
                            <li class="{{ request()->is('dosen/jadwal/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ route('dosen.jadwal.membuat') }}">
                                    <span class="sub-item">Membuat Jadwal Bimbingan</span></a>
                            </li>
                            <li class="{{ request()->is('dosen/jadwal/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ route('dosen.jadwal.melihat') }}">
                                    <span class="sub-item">Melihat Jadwal Bimbingan</span></a>
                            </li>
                            <li class="{{ request()->is('dosen/jadwal/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ route('dosen.jadwal.perubahan') }}">
                                    <span class="sub-item">Mengajukan Perubahan Jadwal Bimbingan</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                
                <!-- TawaranTopik -->
                <li class="nav-item {{ request()->is('dosen/TawaranTopik*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#TawaranTopik"
                        class="{{ request()->is('dosen/TawaranTopik*') ? '' : 'collapsed' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>TawaranTopik</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('dosen/TawaranTopik*') ? 'show' : '' }}" id="TawaranTopik">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('dosen/TawaranTopik/lihat-TawaranTopik') ? 'active' : '' }}">
                                <a href="{{ route('dosen.tawaranTopik.melihat') }}">
                                    <span class="sub-item">Melihat Tawaran Topik</span>
                                </a>
                            </li>
                             <li class="{{ request()->is('dosen/TawaranTopik/lihat-TawaranTopik') ? 'active' : '' }}">
                                <a href="{{ route('dosen.tawaranTopik.mengajukan') }}">
                                    <span class="sub-item">Mengajukan Tawaran Topik</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('dosen/TawaranTopik/lihat-TawaranTopik') ? 'active' : '' }}">
                                <a href="{{ route('dosen.tawaranTopik.mengubah') }}">
                                    <span class="sub-item">Mengubah Tawaran Topik</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('dosen/TawaranTopik/lihat-TawaranTopik') ? 'active' : '' }}">
                                <a href="{{ route('dosen.tawaranTopik.menghapus') }}">
                                    <span class="sub-item">Menghapus Tawaran Topik</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- SIDANG -->
                <li
                    class="nav-item {{ request()->is('sidang') || request()->is('sidang/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables"
                        class="{{ request()->is('sidang') || request()->is('sidang/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('sidang') || request()->is('sidang/*') ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('sidang/dosen') ? 'active' : '' }}">
                                <a href="{{ route('dosen.sidang.melihat') }}">
                                    <span class="sub-item">Melihat jadwal Sidang</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('sidang/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ route('dosen.sidang.list') }}">
                                    <span class="sub-item">List Mahasiswa Sidang</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('sidang/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ route('dosen.sidang.nilai') }}">
                                    <span class="sub-item">Memberi Nilai Sidang</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Pengumuman-->
                <li class="nav-item {{ request()->is('pengumuman') || request()->is('pengumuman/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#maps"
                        class="{{ request()->is('pengumuman') || request()->is('pengumuman/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Pengumuman </p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('pengumuman') || request()->is('pengumuman/*') ? 'show' : '' }}"
                        id="maps">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('pengumuman/lihat') ? 'active' : '' }}">
                                <a href="{{ route('dosen.pengumuman.melihat') }}">
                                    <span class="sub-item">Lihat pengumuman </span>
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
