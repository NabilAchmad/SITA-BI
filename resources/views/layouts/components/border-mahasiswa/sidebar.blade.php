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
                <li
                    class="nav-item {{ request()->is('mahasiswa/TugasAkhir') || request()->is('mahasiswa/TugasAkhir/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts"
                        class="{{ request()->is('mahasiswa/TugasAkhir') || request()->is('mahasiswa/TugasAkhir/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Tugas Akhir</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mahasiswa/TugasAkhir') || request()->is('mahasiswa/TugasAkhir/*') ? 'show' : '' }}"
                        id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mahasiswa/TugasAkhir/ajukan') ? 'active' : '' }}">
                                <a href="{{ url('mahasiswa/TugasAkhir/ajukan') }}">
                                    <span class="sub-item">Ajukan Topik Mandiri</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/TugasAkhir/read') ? 'active' : '' }}">
                                <a href="{{ url('mahasiswa/TugasAkhir/read') }}">
                                    <span class="sub-item">Ajukan Berdasarkan Topik Dosen</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/TugasAkhir/progress') ? 'active' : '' }}">
                                <a href="{{ url('mahasiswa/TugasAkhir/progress') }}">
                                    <span class="sub-item">Progress Tugas Akhir</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Sidang -->
                <li class="nav-item {{ request()->is('mahasiswa/sidang') || request()->is('mahasiswa/sidang/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms"
                        class="{{ request()->is('mahasiswa/sidang') || request()->is('mahasiswa/sidang/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Sidang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mahasiswa/sidang') || request()->is('mahasiswa/sidang/*') ? 'show' : '' }}"
                        id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mahasiswa/sidang/daftar-sidang') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/sidang/daftar-sidang') }}">
                                    <span class="sub-item">Daftar Sidang</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/sidang/lihat-nilai') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/sidang/lihat-nilai') }}">
                                    <span class="sub-item">Nilai Sidang</span></a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/sidang/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/sidang/lihat-jadwal') }}">
                                    <span class="sub-item">Jadwal Sidang</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Bimbingan -->
                <li
                    class="nav-item {{ request()->is('mahasiswa/bimbingan') || request()->is('mahasiswa/bimbingan/*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables"
                        class="{{ request()->is('mahasiswa/bimbingan') || request()->is('mahasiswa/bimbingan/*') ? '' : 'collapsed' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Bimbingan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mahasiswa/bimbingan') || request()->is('mahasiswa/bimbingan/*') ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mahasiswa/bimbingan/ajukan-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/bimbingan/ajukan-jadwal') }}">
                                    <span class="sub-item">Ajukan Jadwal Bimbingan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/bimbingan/lihat-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/bimbingan/lihat-jadwal') }}">
                                    <span class="sub-item">Lihat Jadwal Bimbingan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/bimbingan/revisi') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/bimbingan/revisi') }}">
                                    <span class="sub-item">Revisi</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mahasiswa/bimbingan/perubahan-jadwal') ? 'active' : '' }}">
                                <a href="{{ url('/mahasiswa/bimbingan/perubahan-jadwal') }}">
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
