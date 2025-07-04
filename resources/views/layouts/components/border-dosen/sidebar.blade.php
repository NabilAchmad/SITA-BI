@php
    // Mengambil data user dan role yang sedang login
    $user = auth()->user();
    // Menggunakan collection untuk pengecekan yang lebih mudah dan aman
    $userRoles = $user?->roles->pluck('nama_role') ?? collect();
    $dosenRoles = $user?->dosen?->peranDosenTa?->pluck('peran')->unique() ?? collect();

    // Membuat variabel boolean untuk menyederhanakan logika @if di bawah
    $isKajur = $userRoles->contains('kajur');
    // Pengecekan baru: true jika user memiliki role yang diawali dengan 'kaprodi'
    $isKaprodi = $userRoles->contains(fn($role) => str_starts_with($role, 'kaprodi'));
    $isPembimbing = $dosenRoles->intersect(['pembimbing1', 'pembimbing2'])->isNotEmpty();
    $isPenguji = $dosenRoles->contains(fn($role) => str_starts_with($role, 'penguji'));
@endphp

<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ url('/dosen/dashboard') }}"
                class="logo d-flex align-items-center text-decoration-none px-3 py-2 text-white w-100">
                <div class="d-flex align-items-center justify-content-center bg-light bg-opacity-25 rounded-circle flex-shrink-0 me-2 shadow-sm"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-person-workspace fs-4 text-white"></i>
                </div>
                <span class="fw-bold d-none d-md-inline text-truncate"
                    style="max-width: 100%; white-space: nowrap; letter-spacing: 0.5px; font-size: 1.1rem;">
                    {{-- Logika penamaan disesuaikan dengan role baru --}}
                    @if ($isKajur)
                        Kajur
                    @elseif ($isKaprodi)
                        @if ($userRoles->contains('kaprodi-d3'))
                            Kaprodi D3 BING
                        @elseif ($userRoles->contains('kaprodi-d4'))
                            Kaprodi D4 BING
                        @else
                            Kaprodi
                        @endif
                    @else
                        Dosen
                    @endif
                </span>
            </a>

            {{-- Tombol toggle untuk desktop (sembunyi di mobile) --}}
            <div class="nav-toggle d-none d-lg-block">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>

            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->is('dosen/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dosen/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Akses</h4>
                </li>

                @if ($isPembimbing)
                    <li class="nav-item {{ request()->is('dosen/bimbingan*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.bimbingan.index') }}" class="nav-link">
                            <i class="fas fa-users-cog me-2"></i>
                            <p>Bimbingan</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item {{ request()->is('dosen/tawaran-topik*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.tawaran-topik.index') }}" class="nav-link">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <p>Tawaran Topik</p>
                    </a>
                </li>

                @if ($isKaprodi || $isKajur || $isPenguji)
                    <li class="nav-item {{ request()->is('dosen/sidang*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.sidang.index') }}" class="nav-link">
                            <i class="fas fa-calendar-check me-2"></i>
                            <p>Sidang Mahasiswa</p>
                        </a>
                    </li>
                @endif

                @if ($isKaprodi)
                    <li class="nav-item {{ request()->is('dosen/validasi*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.validasi-tugas-akhir.index') }}" class="nav-link">
                            <i class="fas fa-check-circle me-2"></i>
                            <p>Validasi Judul TA</p>
                        </a>
                    </li>
                @endif

                @if ($isKajur)
                    <li class="nav-item {{ request()->is('dosen/validasi-nilai-tugas-akhir*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.validasi-nilai-tugas-akhir.index') }}" class="nav-link">
                            <i class="fas fa-check-circle me-2"></i>
                            <p>Validasi Nilai TA</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
