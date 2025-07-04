<div class="main-header">
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">

            {{-- Tombol hamburger untuk mobile (sembunyi di desktop) --}}
            <div class="sidenav-toggler sidenav-toggler-inner d-lg-none">
                <a href="#" class="nav-link" id="sidebarToggle" role="button">
                    <i class="bi bi-list fs-2"></i>
                </a>
            </div>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                @include('layouts.components.border-dosen.notification')
                @include('layouts.components.border-dosen.profile')
            </ul>

        </div>
    </nav>
</div>
