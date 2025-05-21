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


                <!-- Tugas Akhir -->
                <li
                    class="nav-item {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts"
                        class="{{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Tugas Akhir</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('tugas-akhir') || request()->is('tugas-akhir/*') ? 'show' : '' }}"
                        id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('/mahasiswa/ajukan-tugas-akhir') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/ajukan-tugas-akhir') }}">
                                    <span class="sub-item">Ajukan Topik Mandiri</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('tugas-akhir/read') ? 'active' : '' }}">
                                <a href="{{ url('/tugas-akhir/read') }}">
                                    <span class="sub-item">Ajukan Berdasarkan Topik Dosen</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('tugas-akhir/read') ? 'active' : '' }}">
                                <a href="{{ url('/tugas-akhir/read') }}">
                                    <span class="sub-item">Membatalkan Tugas Akhir</span>
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
                            <li class="{{ request()->is('sidang/tentukan-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/sidang/tentukan-jadwal') }}">
                                    <span class="sub-item">Daftar Sidang</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/sidang/lihat-jadwal') }}">
                                    <span class="sub-item">Nilai Sidang</span></a>
                            </li>
                            <li class="{{ request()->is('sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/sidang/lihat-jadwal') }}">
                                    <span class="sub-item">Jadwal Sidang</span></a>
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
                        <p>Bimbingan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('kelola-akun') || request()->is('kelola-akun/*') ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('kelola-akun/dosen') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/dosen') }}">
                                    <span class="sub-item">Ajukan Jadwal Bimbingan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('kelola-akun/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/mahasiswa') }}">
                                    <span class="sub-item">Lihat Jadwal Bimbingan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('kelola-akun/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/mahasiswa') }}">
                                    <span class="sub-item">Revisi</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('kelola-akun/mahasiswa') ? 'active' : '' }}">
                                <a href="{{ url('/kelola-akun/mahasiswa') }}">
                                    <span class="sub-item">Ajukan Perubahan Jadwal Bimbingan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
